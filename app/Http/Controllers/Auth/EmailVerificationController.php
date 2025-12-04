<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class EmailVerificationController extends Controller
{
    /**
     * Verify user's email address via token
     */
    public function verify($token)
    {
        // Find user by verification token
        $user = User::where('verification_token', $token)->first();

        // Check if token exists
        if (!$user) {
            return redirect()->route('login')
                ->with('error', 'Link xác thực không hợp lệ.');
        }

        // Check if token has expired (24 hours)
        if ($user->verification_expires_at && Carbon::parse($user->verification_expires_at)->isPast()) {
            return redirect()->route('login')
                ->with('error', 'Link xác thực đã hết hạn. Vui lòng đăng ký lại.');
        }

        // Check if already verified
        if ($user->email_verified_at) {
            return redirect()->route('login')
                ->with('info', 'Email đã được xác thực trước đó. Vui lòng đăng nhập.');
        }

        // Verify the email
        $user->email_verified_at = now();
        $user->verification_token = null;
        $user->verification_expires_at = null;
        $user->save();

        // Auto login the user
        Auth::login($user);

        // Redirect based on role
        if ($user->role_id == 1) {
            return redirect()->route('admin.dashboard')
                ->with('success', 'Email đã được xác thực thành công! Chào mừng bạn đến với Phonezy.');
        }

        return redirect()->route('client.index')
            ->with('success', 'Email đã được xác thực thành công! Chào mừng bạn đến với Phonezy.');
    }
}
