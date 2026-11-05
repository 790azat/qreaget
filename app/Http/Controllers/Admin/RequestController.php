<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Department;
use App\Models\RequestEvent;
use App\Models\ServiceRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RequestController extends Controller
{
    private function filtered(Request $request)
    {
        $q = ServiceRequest::with(['department', 'assignee', 'user']);
        if ($s = trim((string) $request->query('q'))) {
            $q->where(function ($w) use ($s) {
                $w->where('name', 'like', "%$s%")->orWhere('phone', 'like', "%$s%")
                    ->orWhere('email', 'like', "%$s%")->orWhere('number', 'like', "%$s%")
                    ->orWhere('message', 'like', "%$s%");
            });
        }
        foreach (['status', 'department_id', 'priority', 'assigned_to'] as $f) {
            if ($request->filled($f)) {
                $q->where($f, $request->query($f));
            }
        }
        if ($request->filled('from')) {
            $q->whereDate('created_at', '>=', $request->query('from'));
        }
        if ($request->filled('to')) {
            $q->whereDate('created_at', '<=', $request->query('to'));
        }
        $sort = in_array($request->query('sort'), ['created_at', 'deadline', 'status', 'priority'], true) ? $request->query('sort') : 'created_at';

        return $q->orderBy($sort, $request->query('dir') === 'asc' ? 'asc' : 'desc');
    }

    public function index(Request $request)
    {
        return view('admin.requests.index', [
            'items' => $this->filtered($request)->paginate(25)->withQueryString(),
            'departments' => Department::orderBy('sort')->get(),
            'staff' => User::whereIn('role', ['admin', 'manager'])->get(),
        ]);
    }

    public function create()
    {
        return view('admin.requests.create', ['departments' => Department::orderBy('sort')->get()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:120',
            'phone' => 'required|string|max:40',
            'email' => 'nullable|email',
            'department_id' => 'nullable|exists:departments,id',
            'client_type' => ['nullable', Rule::in(ServiceRequest::CLIENT_TYPES)],
            'message' => 'required|string',
        ]);
        $r = ServiceRequest::create($data + ['ip' => $request->ip()]);
        RequestEvent::create(['service_request_id' => $r->id, 'user_id' => auth()->id(), 'type' => 'created', 'visible_to_client' => true]);
        ActivityLog::record('request.create', $r->fresh()->number);

        return redirect()->route('admin.requests.show', $r)->with('success', 'Saved');
    }

    public function show(ServiceRequest $serviceRequest)
    {
        return view('admin.requests.show', [
            'r' => $serviceRequest->load(['department', 'user', 'assignee', 'events.user']),
            'departments' => Department::orderBy('sort')->get(),
            'staff' => User::whereIn('role', ['admin', 'manager'])->get(),
            'others' => ServiceRequest::where('id', '!=', $serviceRequest->id)
                ->where(fn ($q) => $q->where('phone', $serviceRequest->phone)
                    ->when($serviceRequest->email, fn ($q) => $q->orWhere('email', $serviceRequest->email)))
                ->latest()->take(10)->get(),
        ]);
    }

    public function update(Request $request, ServiceRequest $serviceRequest)
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(ServiceRequest::STATUSES)],
            'priority' => ['required', Rule::in(ServiceRequest::PRIORITIES)],
            'department_id' => 'nullable|exists:departments,id',
            'assigned_to' => 'nullable|exists:users,id',
            'price' => 'nullable|numeric|min:0',
            'deadline' => 'nullable|date',
            'admin_note' => 'nullable|string|max:10000',
            'name' => 'required|string|max:120',
            'phone' => 'required|string|max:40',
            'email' => 'nullable|email',
        ]);

        $oldStatus = $serviceRequest->status;
        $serviceRequest->update($data);

        if ($oldStatus !== $data['status']) {
            RequestEvent::create([
                'service_request_id' => $serviceRequest->id,
                'user_id' => auth()->id(),
                'type' => 'status',
                'body' => $data['status'],
                'visible_to_client' => true,
            ]);
        }
        ActivityLog::record('request.update', $serviceRequest->number);

        return back()->with('success', 'Saved');
    }

    public function event(Request $request, ServiceRequest $serviceRequest)
    {
        $data = $request->validate(['body' => 'required|string|max:5000', 'visible_to_client' => 'nullable|boolean']);
        RequestEvent::create([
            'service_request_id' => $serviceRequest->id,
            'user_id' => auth()->id(),
            'type' => 'comment',
            'body' => $data['body'],
            'visible_to_client' => $request->boolean('visible_to_client'),
        ]);

        return back()->with('success', 'Saved');
    }

    public function bulk(Request $request)
    {
        $data = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer',
            'action' => 'required|string',
        ]);
        $q = ServiceRequest::whereIn('id', $data['ids']);
        if ($data['action'] === 'delete') {
            abort_unless(auth()->user()->isAdmin(), 403);
            $q->delete();
        } elseif (in_array($data['action'], ServiceRequest::STATUSES, true)) {
            $q->update(['status' => $data['action']]);
        }
        ActivityLog::record('request.bulk.'.$data['action'], implode(',', $data['ids']));

        return back()->with('success', 'Done');
    }

    public function destroy(ServiceRequest $serviceRequest)
    {
        abort_unless(auth()->user()->isAdmin(), 403);
        ActivityLog::record('request.delete', $serviceRequest->number);
        $serviceRequest->delete();

        return redirect()->route('admin.requests.index')->with('success', 'Deleted');
    }

    public function export(Request $request)
    {
        $rows = $this->filtered($request)->get();
        $name = 'requests-'.now()->format('Y-m-d').'.csv';

        return response()->streamDownload(function () use ($rows) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF");
            fputcsv($out, ['Number', 'Date', 'Status', 'Priority', 'Name', 'Phone', 'Email', 'Department', 'Client type', 'Price', 'Deadline', 'Message', 'Note']);
            foreach ($rows as $r) {
                fputcsv($out, [
                    $r->number, $r->created_at->format('Y-m-d H:i'), $r->status, $r->priority, $r->name, $r->phone,
                    $r->email, $r->department?->name_hy, $r->client_type, $r->price, $r->deadline?->format('Y-m-d'),
                    $r->message, $r->admin_note,
                ]);
            }
            fclose($out);
        }, $name, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
}
