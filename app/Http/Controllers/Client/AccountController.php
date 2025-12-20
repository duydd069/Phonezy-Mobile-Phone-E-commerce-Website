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

    /**
     * Cập nhật thông tin tài khoản
     */
    public function update(Request $request)
    {
        $user = auth()->user();
        
        if (!$user) {
            return redirect()->route('client.login')->with('error', 'Vui lòng đăng nhập.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        $user->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        return redirect()->route('client.account.index')->with('success', 'Cập nhật thông tin thành công!');
    }
}
