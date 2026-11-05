<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $q = User::withCount('serviceRequests');
        if ($s = $request->query('q')) {
            $q->where(fn ($w) => $w->where('name', 'like', "%$s%")->orWhere('email', 'like', "%$s%")->orWhere('phone', 'like', "%$s%"));
        }
        if ($request->filled('role')) {
            $q->where('role', $request->query('role'));
        }

        return view('admin.users.index', ['items' => $q->latest()->paginate(30)->withQueryString()]);
    }

    public function create()
    {
        return view('admin.users.form', ['item' => new User(['role' => 'client'])]);
    }

    public function store(Request $request)
    {
        $u = User::create($this->validated($request));
        ActivityLog::record('user.create', $u->email);

        return redirect()->route('admin.users.index')->with('success', 'Saved');
    }

    public function edit(User $user)
    {
        return view('admin.users.form', ['item' => $user]);
    }

    public function update(Request $request, User $user)
    {
        $data = $this->validated($request, $user);
        if (blank($data['password'] ?? null)) {
            unset($data['password']);
        }
        if ($user->id === auth()->id()) {
            unset($data['role'], $data['is_blocked']);
        }
        $user->update($data);
        ActivityLog::record('user.update', $user->email);

        return redirect()->route('admin.users.index')->with('success', 'Saved');
    }

    public function destroy(User $user)
    {
        abort_if($user->id === auth()->id(), 422);
        ActivityLog::record('user.delete', $user->email);
        $user->delete();

        return back()->with('success', 'Deleted');
    }

    private function validated(Request $request, ?User $user = null): array
    {
        $data = $request->validate([
            'name' => 'required|string|max:120',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user?->id)],
            'phone' => 'nullable|string|max:40',
            'role' => ['required', Rule::in(User::ROLES)],
            'password' => [$user ? 'nullable' : 'required', 'string', 'min:8'],
        ]);
        $data['is_blocked'] = $request->boolean('is_blocked');

        return $data;
    }
}
