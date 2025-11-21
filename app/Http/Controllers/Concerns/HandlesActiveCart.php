<?php

namespace App\Http\Controllers\Concerns;

use App\Models\Cart;

trait HandlesActiveCart
{
    protected function resolveCartUserId(): int
    {
        return auth()->check() ? (int) auth()->id() : 1;
    }

    protected function getOrCreateActiveCart(): Cart
    {
        $userId = $this->resolveCartUserId();

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
}


