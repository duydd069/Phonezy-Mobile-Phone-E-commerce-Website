<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PreventAdminAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Kiểm tra người dùng đã đăng nhập chưa
        if (Auth::check()) {
            $user = Auth::user();

            // Nếu là admin (role_id == 1), chuyển hướng về admin dashboard
            if ($user->role_id == 1) {
                return redirect()->route('admin.dashboard')->with('error', 'Admin không được phép truy cập trang khách hàng.');
            }
        }

        return $next($request);
    }
}