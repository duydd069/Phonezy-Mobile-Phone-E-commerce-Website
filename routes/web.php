<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ChatbotController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Client\CheckoutController;

// Route::get('/', function () {
//     return view('home');
// });

Route::get('/', function () {
    return redirect()->route('client.index');
});

Route::prefix('api')->group(function () {
    Route::post('/chatbot', ChatbotController::class)->name('api.chatbot');
});

// Electro frontend routes (Client)
Route::prefix('client')->name('client.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Client\ProductController::class, 'index'])->name('index');
    Route::get('/p/{product}', [\App\Http\Controllers\Client\ProductController::class, 'show'])->name('product.show');
    Route::get('/store', function () {
        return view('electro.store');
    })->name('store');
    Route::get('/category/{slug}', [\App\Http\Controllers\Client\CategoryController::class, 'show'])->name('category.show');
    Route::get('/promotions', [\App\Http\Controllers\Client\ProductController::class, 'promotions'])->name('promotions');
    Route::view('/assistant', 'client.chatbot')->name('assistant');

    Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/checkout/success/{order}', [CheckoutController::class, 'success'])->name('checkout.success');
    Route::post('/checkout/validate-coupon', [CheckoutController::class, 'validateCoupon'])->name('checkout.validate-coupon');
    Route::get('/payment/vnpay/return', [\App\Http\Controllers\Client\VnpayController::class, 'return'])->name('vnpay.return');
    Route::post('/payment/vnpay/ipn', [\App\Http\Controllers\Client\VnpayController::class, 'ipn'])->name('vnpay.ipn');

    // Comments routes
    Route::post('/comments/{product}', [\App\Http\Controllers\Client\CommentController::class, 'store'])->name('comments.store');
    Route::get('/comments/{product}', [\App\Http\Controllers\Client\CommentController::class, 'index'])->name('comments.index');

    Route::get('/login', [\App\Http\Controllers\AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login'])->name('login.post');

    Route::get('/register', [\App\Http\Controllers\Auth\RegisterController::class, 'show'])->name('register');
    Route::post('/register', [\App\Http\Controllers\Auth\RegisterController::class, 'store'])->name('register.store');
});

use App\Http\Controllers\Client\CartController;
use App\Http\Controllers\Client\OrderController;
use App\Http\Controllers\Client\VnpayController;

Route::middleware(['web'])->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
    Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
});

// Client Account - Yêu cầu đăng nhập
Route::middleware(['auth'])->prefix('client')->name('client.')->group(function () {
    Route::get('/account', [\App\Http\Controllers\Client\AccountController::class, 'index'])->name('account.index');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/coupons', [\App\Http\Controllers\Client\CouponController::class, 'index'])->name('coupons.index');
    
    // Wishlist routes
    Route::get('/wishlist', [\App\Http\Controllers\Client\WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/add', [\App\Http\Controllers\Client\WishlistController::class, 'add'])->name('wishlist.add');
    Route::post('/wishlist/remove', [\App\Http\Controllers\Client\WishlistController::class, 'remove'])->name('wishlist.remove');
    Route::post('/wishlist/toggle', [\App\Http\Controllers\Client\WishlistController::class, 'toggle'])->name('wishlist.toggle');
});

// Registration routes (show client-styled view)
Route::get('/register', [RegisterController::class, 'show'])->name('register');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

// Login / Logout routes (show client-styled view)
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Email verification routes
Route::get('/email/verify/{token}', [\App\Http\Controllers\Auth\EmailVerificationController::class, 'verify'])->name('email.verify');
Route::get('/verification-sent', function () {
    return view('electro.auth.verification-sent');
})->name('verification.sent');

// Admin routes - Yêu cầu đăng nhập và quyền admin
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductVariantController;
use App\Http\Controllers\Admin\RevenueReportController;

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::resource('brands', \App\Http\Controllers\Admin\BrandController::class);
    Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class);
    
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
    Route::resource('colors', \App\Http\Controllers\Admin\ColorController::class);
    Route::resource('storages', \App\Http\Controllers\Admin\StorageController::class);
    Route::resource('versions', \App\Http\Controllers\Admin\VersionController::class);
    Route::resource('coupons', \App\Http\Controllers\Admin\CouponController::class);

    // Products routes
    Route::get('/products',                  [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/create',           [ProductController::class, 'create'])->name('products.create');
    Route::post('/products',                 [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{id}',             [ProductController::class, 'show'])->name('products.show');
    Route::get('/products/{id}/edit',        [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{id}',             [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{id}',          [ProductController::class, 'destroy'])->name('products.destroy');

    // Product variants routes (nested under product)
    Route::prefix('products/{productId}')->name('products.')->group(function () {
        Route::get('/variants',                 [ProductVariantController::class, 'index'])->name('variants.index');
        Route::get('/variants/create',          [ProductVariantController::class, 'create'])->name('variants.create');
        Route::post('/variants',                [ProductVariantController::class, 'store'])->name('variants.store');
        Route::get('/variants/{variantId}/edit', [ProductVariantController::class, 'edit'])->name('variants.edit');
        Route::patch('/variants/{variantId}/stock', [ProductVariantController::class, 'updateStock'])->name('variants.updateStock');
        Route::put('/variants/{variantId}',     [ProductVariantController::class, 'update'])->name('variants.update');
        Route::delete('/variants/{variantId}',  [ProductVariantController::class, 'destroy'])->name('variants.destroy');
    });

    // Orders routes
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::put('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::post('/orders/{order}/confirm-payment', [AdminOrderController::class, 'confirmPayment'])->name('orders.confirm-payment');
    Route::post('/orders/{order}/refund', [AdminOrderController::class, 'refund'])->name('orders.refund');
    Route::post('/orders/{order}/query-transaction', [AdminOrderController::class, 'queryTransaction'])->name('orders.query-transaction');
    Route::post('/orders/{order}/generate-test-transaction', [AdminOrderController::class, 'generateTestTransaction'])->name('orders.generate-test-transaction');

    // Revenue Report routes
    Route::get('/revenue', [RevenueReportController::class, 'index'])->name('revenue.index');
    Route::get('/revenue/export', [RevenueReportController::class, 'export'])->name('revenue.export');
});
