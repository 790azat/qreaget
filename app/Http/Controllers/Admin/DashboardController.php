<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\ChatSession;
use App\Models\Department;
use App\Models\ServiceRequest;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $byStatus = ServiceRequest::selectRaw('status, count(*) as c')->groupBy('status')->pluck('c', 'status');

        $days = collect(range(13, 0))->map(fn ($i) => now()->subDays($i)->toDateString());
        $raw = ServiceRequest::where('created_at', '>=', now()->subDays(14)->startOfDay())
            ->get(['created_at'])->groupBy(fn ($r) => $r->created_at->toDateString())->map->count();
        $chart = $days->map(fn ($d) => ['day' => $d, 'count' => (int) ($raw[$d] ?? 0)]);

        $byDept = Department::withCount('serviceRequests')->orderByDesc('service_requests_count')->take(10)->get();

        return view('admin.dashboard', [
            'stats' => [
                'requests_total' => ServiceRequest::count(),
                'requests_new' => (int) ($byStatus['new'] ?? 0),
                'requests_month' => ServiceRequest::where('created_at', '>=', now()->startOfMonth())->count(),
                'clients' => User::where('role', 'client')->count(),
                'chats_unread' => ChatSession::where('unread_admin', true)->count(),
                'chats_open' => ChatSession::where('status', 'open')->count(),
            ],
            'byStatus' => $byStatus,
            'chart' => $chart,
            'byDept' => $byDept,
            'latest' => ServiceRequest::with('department')->latest()->take(8)->get(),
            'chats' => ChatSession::with('lastMessage')->latest('last_message_at')->take(6)->get(),
        ]);
    }

    public function activity()
    {
        return view('admin.activity', ['logs' => ActivityLog::with('user')->latest()->paginate(40)]);
    }
}
