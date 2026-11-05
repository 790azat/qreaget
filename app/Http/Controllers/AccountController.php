<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\RequestEvent;
use App\Models\ServiceRequest;
use App\Services\Telegram;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AccountController extends Controller
{
    public function index(Request $request)
    {
        $requests = $request->user()->serviceRequests()->with('department')->latest()->paginate(10);

        return view('account.index', compact('requests'));
    }

    public function create()
    {
        return view('account.create', ['departments' => Department::active()->get()]);
    }

    public function show(Request $request, ServiceRequest $serviceRequest)
    {
        abort_unless($serviceRequest->user_id === $request->user()->id, 404);
        $events = $serviceRequest->events()->where('visible_to_client', true)->with('user')->get();

        return view('account.show', ['r' => $serviceRequest->load('department'), 'events' => $events]);
    }

    public function comment(Request $request, ServiceRequest $serviceRequest, Telegram $telegram)
    {
        abort_unless($serviceRequest->user_id === $request->user()->id, 404);
        $data = $request->validate(['body' => 'required|string|max:3000']);
        RequestEvent::create([
            'service_request_id' => $serviceRequest->id,
            'user_id' => $request->user()->id,
            'type' => 'client_comment',
            'body' => $data['body'],
            'visible_to_client' => true,
        ]);
        $telegram->send("💬 <b>Client comment on {$serviceRequest->number}</b>\n"
            .Telegram::e($request->user()->name).":\n".Telegram::e($data['body'])
            ."\n\n".route('admin.requests.show', $serviceRequest));

        return back()->with('success', __('site.account.comment_added'));
    }

    public function profile(Request $request)
    {
        return view('account.profile', ['user' => $request->user()]);
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();
        $data = $request->validate([
            'name' => 'required|string|max:120',
            'phone' => 'required|string|max:40',
            'email' => 'required|email|max:150|unique:users,email,'.$user->id,
            'current_password' => 'nullable|required_with:password|string',
            'password' => ['nullable', 'confirmed', Password::min(8)],
        ]);

        if (filled($data['password'] ?? null)) {
            if (! Hash::check($data['current_password'], $user->password)) {
                return back()->withErrors(['current_password' => __('site.account.wrong_password')]);
            }
            $user->password = $data['password'];
        }
        $user->fill(collect($data)->only(['name', 'phone', 'email'])->all())->save();

        return back()->with('success', __('site.account.saved'));
    }
}
