<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    /**
     * Hiển thị trang tài khoản của user
     */
    public function index()
    {
        $user = auth()->user();
        
        if (!$user) {
            return redirect()->route('client.login')->with('error', 'Vui lòng đăng nhập để xem tài khoản.');
        }

        // Load relationships để tính toán thống kê
        $user->load('orders');

        return view('electro.account.index', compact('user'));
    }
}
