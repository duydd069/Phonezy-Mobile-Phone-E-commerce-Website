<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::query();

        if ($search = $request->string('q')->toString()) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by role_id instead of is_admin
        if ($request->filled('role_id')) {
            $query->where('role_id', $request->input('role_id'));
        }

        $users = $query->orderByDesc('id')->paginate(15)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function create(): View
    {
        return view('admin.users.create');
    }

    public function store(UserRequest $request): RedirectResponse
    {
        $data = $request->validated();
        
        // Hash password
        if (!empty($data['password'])) {
            $data['password_hash'] = Hash::make($data['password']);
            unset($data['password']);
        }

        // Set role_id (default to 2 = customer if not provided)
        $data['role_id'] = $request->input('role_id', 2);

        User::create($data);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully');
    }

    public function show(User $user): View
    {
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user): View
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(UserRequest $request, User $user): RedirectResponse
    {
        $data = $request->validated();

        // Only update password if provided
        if (!empty($data['password'])) {
            $data['password_hash'] = Hash::make($data['password']);
            unset($data['password']);
        } else {
            unset($data['password']);
        }

        // Update role_id
        $data['role_id'] = $request->input('role_id', $user->role_id);
        
        // Handle email_verified_at
        if (empty($data['email_verified_at'])) {
            $data['email_verified_at'] = null;
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully');
    }

    /**
     * Ban a user
     */
    public function ban(User $user): RedirectResponse
    {
        $user->update(['is_banned' => 1]);
        return redirect()->route('admin.users.index')->with('success', 'User banned successfully');
    }

    /**
     * Unban a user
     */
    public function unban(User $user): RedirectResponse
    {
        $user->update(['is_banned' => 0]);
        return redirect()->route('admin.users.index')->with('success', 'User unbanned successfully');
    }
}

