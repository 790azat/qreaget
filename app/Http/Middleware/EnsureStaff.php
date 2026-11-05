<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureStaff
{
    public function handle(Request $request, Closure $next, string $role = 'staff')
    {
        $user = $request->user();
        if (! $user || $user->is_blocked) {
            return redirect()->route('login');
        }
        $ok = $role === 'admin' ? $user->isAdmin() : $user->isStaff();
        abort_unless($ok, 403);

        return $next($request);
    }
}
