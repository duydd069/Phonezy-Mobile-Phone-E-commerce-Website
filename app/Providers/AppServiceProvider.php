<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\URL;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Wishlist;
use Illuminate\Pagination\Paginator;


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
        // Force HTTPS URLs when APP_URL is HTTPS (for ngrok and production)
        if (str_starts_with(config('app.url'), 'https://')) {
            URL::forceScheme('https');
        }
        //phân trang
         Paginator::useBootstrapFive();
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
            
            // Đếm số lượng sản phẩm trong wishlist
            $wishlistCount = 0;
            if ($userId && $userId !== 1) { // Chỉ đếm nếu đã đăng nhập (không phải user_id = 1)
                $wishlistCount = Wishlist::where('user_id', $userId)->count();
            }
            $view->with('wishlistCount', $wishlistCount);
            
            // Chỉ set categories nếu controller chưa set (để tránh ghi đè pagination)
            if (!$view->offsetExists('categories')) {
                $view->with('categories', Category::all());
            }
        });
    }
}
