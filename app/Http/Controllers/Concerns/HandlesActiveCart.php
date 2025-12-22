<?php

namespace App\Http\Controllers\Concerns;

use App\Models\Cart;

trait HandlesActiveCart
{
    protected function resolveCartUserId(): int
    {
        if (!auth()->check()) {
            throw new \Illuminate\Auth\AuthenticationException();
        }
        return (int) auth()->id();
    }

    protected function getOrCreateActiveCart(): Cart
    {
        $userId = $this->resolveCartUserId();

        // Đảm bảo chỉ có 1 cart active cho mỗi user
        // Nếu có nhiều carts active, đóng tất cả và tạo cart mới
        $activeCarts = Cart::where('user_id', $userId)
            ->where('status', 'active')
            ->orderBy('updated_at', 'desc')
            ->get();

        if ($activeCarts->count() > 1) {
            // Đóng tất cả carts cũ trừ cart mới nhất
            Cart::where('user_id', $userId)
                ->where('status', 'active')
                ->where('id', '!=', $activeCarts->first()->id)
                ->update(['status' => 'converted']);
        }

        // Lấy cart active mới nhất của user, nếu không có thì tạo mới
        $cart = Cart::where('user_id', $userId)
            ->where('status', 'active')
            ->orderBy('updated_at', 'desc')
            ->first();

        if (!$cart) {
            $cart = Cart::create([
                'user_id' => $userId,
                'status'  => 'active',
            ]);
        }

        return $cart;
    }
}

