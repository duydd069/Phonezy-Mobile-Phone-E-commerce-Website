<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Cart;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Biến dùng cho mọi view: số lượng sản phẩm trong giỏ
        View::composer('*', function ($view) {
            // Nếu chưa login thì tạm dùng user_id = 1 (giống CartController)
            $userId = auth()->check() ? auth()->id() : 1;

            $cart = Cart::with('items')
                ->where('user_id', $userId)
                ->where('status', 'active')
                ->first();

            $count = $cart ? $cart->items->sum('quantity') : 0;

            $view->with('cartCount', $count);
        });
    }
}
