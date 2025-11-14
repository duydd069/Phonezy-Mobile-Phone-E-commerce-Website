<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisterController extends Controller
{
    /**
     * Show the registration form.
     */
    public function show()
    {
        return view('auth.register');
    }

    /**
     * Handle registration submission.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password_hash' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password_hash' => Hash::make($data['password_hash']),
            'role_id' => 2, // 2 = normal user
        ]);

        auth()->login($user);

        $user = auth()->user();
        if ($user && isset($user->role_id) && $user->role_id == 2) {
            return redirect()->intended(route('client.index'));
        }

        return redirect('/admin');
    }
}
