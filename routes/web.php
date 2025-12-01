<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Client\CheckoutController;
use App\Http\Controllers\Client\PaymentController;

// Route::get('/', function () {
//     return view('home');
// });

Route::get('/', function () { return redirect()->route('client.index'); });

// Electro frontend routes (Client)
Route::prefix('client')->name('client.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Client\ProductController::class, 'index'])->name('index');
    Route::get('/p/{product}', [\App\Http\Controllers\Client\ProductController::class, 'show'])->name('product.show');
    Route::get('/store', function () {
        return view('electro.store');
    })->name('store');

    Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/checkout/success/{order}', [CheckoutController::class, 'success'])->name('checkout.success');

    // MoMo Payment routes
    Route::get('/payment/momo/return', [PaymentController::class, 'momoReturn'])->name('payment.momo.return');
    Route::post('/payment/momo/notify', [PaymentController::class, 'momoNotify'])->name('payment.momo.notify');
    
    // MoMo Mock Payment routes (chỉ dùng để test)
    Route::get('/payment/momo/mock', [PaymentController::class, 'momoMock'])->name('payment.momo.mock');
    Route::post('/payment/momo/mock/process', [PaymentController::class, 'momoMockProcess'])->name('payment.momo.mock.process');
    
    // Bank Transfer Webhook (để tự động xác nhận khi nhận tiền)
    Route::post('/payment/bank/webhook', [\App\Http\Controllers\Client\BankWebhookController::class, 'handle'])->name('payment.bank.webhook');
    Route::post('/payment/bank/webhook/test', [\App\Http\Controllers\Client\BankWebhookController::class, 'test'])->name('payment.bank.webhook.test');
    
    // Bank Transfer Webhook (để tự động xác nhận khi nhận tiền)
    Route::post('/payment/bank/webhook', [\App\Http\Controllers\Client\BankWebhookController::class, 'handle'])->name('payment.bank.webhook');
    Route::post('/payment/bank/webhook/test', [\App\Http\Controllers\Client\BankWebhookController::class, 'test'])->name('payment.bank.webhook.test');

    Route::get('/login', [\App\Http\Controllers\AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login'])->name('login.post');

    Route::get('/register', [\App\Http\Controllers\Auth\RegisterController::class, 'show'])->name('register');
    Route::post('/register', [\App\Http\Controllers\Auth\RegisterController::class, 'store'])->name('register.store');
});

use App\Http\Controllers\Client\CartController;
use App\Http\Controllers\Client\OrderController;

Route::middleware(['web'])->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
    Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
});

// Client Orders - Yêu cầu đăng nhập
Route::middleware(['auth'])->prefix('client')->name('client.')->group(function () {
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
});

// Registration routes (show client-styled view)
Route::get('/register', [RegisterController::class, 'show'])->name('register');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

// Login / Logout routes (show client-styled view)
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin routes - Yêu cầu đăng nhập và quyền admin
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        return view('admin.dashboard');
    })->name('dashboard');
    
    Route::resource('brands', \App\Http\Controllers\Admin\BrandController::class);

    Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class);

    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
     Route::resource('coupons', \App\Http\Controllers\Admin\CouponController::class);

    // Products routes
    Route::get('/products',            [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/create',     [ProductController::class, 'create'])->name('products.create');
    Route::post('/products',           [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{id}',       [ProductController::class, 'show'])->name('products.show');
    Route::get('/products/{id}/edit',  [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{id}',       [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{id}',    [ProductController::class, 'destroy'])->name('products.destroy');

    // Orders routes
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::put('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::put('/orders/{order}/payment-status', [AdminOrderController::class, 'updatePaymentStatus'])->name('orders.update-payment-status');
});


