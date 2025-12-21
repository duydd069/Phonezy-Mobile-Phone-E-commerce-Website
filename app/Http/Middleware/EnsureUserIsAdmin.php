<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Kiểm tra người dùng đã đăng nhập chưa
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Bạn cần đăng nhập để truy cập trang này.');
        }

        $user = Auth::user();

        //  BỊ BAN → ĐÁ VỀ LOGIN + LOGOUT
        if ($user->status === 'banned') {
            Auth::logout();

            return redirect()
                ->route('login')
                ->with('error', 'Tài khoản của bạn đã bị khóa.');
        }
        
        // Kiểm tra quyền admin (role_id == 1)
        $user = Auth::user();
        if (!isset($user->role_id) || $user->role_id != 1) {
            return redirect()->route('client.index')->with('error', 'Bạn không có quyền truy cập trang quản trị.');
        }

        return $next($request);
    }
}
