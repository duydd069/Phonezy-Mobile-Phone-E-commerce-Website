<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register()
    {
        return view('electro.auth.register');
    }

    /**
     * Show the login form.
     */
    public function showLogin()
    {
        return view('electro.auth.login');
    }

    /**
     * Attempt to authenticate the user.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required','email'],
            'password' => ['required'],  // Vẫn giữ là 'password' trong validate
        ]);

        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();
            
            // Check if user is banned
            if ($user && $user->is_banned) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                
                return back()->withErrors([
                    'email' => 'Tài khoản của bạn đã bị cấm. Vui lòng liên hệ quản trị viên.'
                ])->onlyInput('email');
            }
            
            $request->session()->regenerate();

            if ($user && isset($user->role_id)) {
                if ($user->role_id == 1) {
                    // Admin redirect
                    return redirect()->intended(route('admin.dashboard'));
                } elseif ($user->role_id == 2) {
                    // User redirect to client
                    return redirect()->intended(route('client.index'));
                }
            }

            // Default fallback
            return redirect()->intended('/');
        }

        return back()->withErrors(['email' => 'Email hoặc mật khẩu không đúng'])->onlyInput('email');
    }


    /**
     * Log the user out.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}