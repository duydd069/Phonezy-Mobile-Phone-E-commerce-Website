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
        // dd($request->all());
         $query = User::query();

           if ($request->filled('q')) {
        $search = $request->q;
        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%");
        });
    }

    // 🎭 Filter role
    if ($request->filled('role_id')) {
        $query->where('role_id', (int) $request->role_id);
    }

    // 🚫 Filter status
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }
        $users = $query->paginate(15)->withQueryString();


        return view('admin.users.index', compact('users'));
    }

    public function create(): View
    {
        return view('admin.users.create');
    }

    public function store(UserRequest $request): RedirectResponse
    {
        $data = $request->validated();
        
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $data['is_admin'] = $request->has('is_admin') ? 1 : 0;
        
        if (empty($data['email_verified_at'])) {
            $data['email_verified_at'] = null;
        }

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

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $data['role_id'] = $request->has('is_admin') ? 1 : 2;
        
        if (empty($data['email_verified_at'])) {
            $data['email_verified_at'] = null;
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully');
    }

    /**
     * Ban a user
     */
    // public function ban(User $user): RedirectResponse
    // {
    //     $user->update(['status' => 'banned']);
    //     return redirect()->route('admin.users.index')->with('success', 'Đã cấm người dùng thành công');
    // }

    // /**
    //  * Unban a user
    //  */
    // public function unban(User $user): RedirectResponse
    // {
    //     $user->update(['status' => 'active']);
    //     return redirect()->route('admin.users.index')->with('success', 'Đã bỏ cấm người dùng thành công');
    // }

   public function toggleBan(User $user)
        {
            $user->status = $user->status === 'banned'
                ? 'active'
                : 'banned';

            $user->save(); // ⚠️ BẮT BUỘC

            return back();
        }


}

