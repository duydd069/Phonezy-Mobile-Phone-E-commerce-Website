<?php

namespace App\Http\Controllers\Concerns;

use App\Models\Cart;

trait HandlesActiveCart
{
    protected function resolveCartUserId(): ?int
    {
        return auth()->check() ? (int) auth()->id() : null;
    }

    protected function getOrCreateActiveCart(): Cart
    {
        $userId = $this->resolveCartUserId();

        if ($userId) {
            // User đã đăng nhập
            return Cart::firstOrCreate(
                [
                    'user_id' => $userId,
                    'status'  => 'active',
                ],
                [
                    'status' => 'active',
                ]
            );
        }

        // Guest: sử dụng session để lưu cart_id
        $cartId = session('guest_cart_id');
        
        if ($cartId) {
            $cart = Cart::where('id', $cartId)
                ->whereNull('user_id')
                ->where('status', 'active')
                ->first();
            
            if ($cart) {
                return $cart;
            }
        }

        // Tạo cart mới cho guest
        $cart = Cart::create([
            'user_id' => null,
            'status'  => 'active',
        ]);

        session(['guest_cart_id' => $cart->id]);

        return $cart;
    }
}


