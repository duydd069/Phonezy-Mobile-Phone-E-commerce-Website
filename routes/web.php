<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductVariantController;
use App\Http\Controllers\Client\CartController;
use App\Http\Controllers\Client\OrderController;

Route::get('/', fn () => redirect()->route('client.index'));

// Electro frontend routes (Client)
Route::prefix('client')->group(function () {
    Route::get('/', [\App\Http\Controllers\Client\ProductController::class, 'index'])->name('client.index');
    Route::get('/p/{product}', [\App\Http\Controllers\Client\ProductController::class, 'show'])->name('client.product.show');
    Route::get('/store', fn () => view('electro.store'))->name('client.store');

    // Order routes
    Route::get('/checkout', [OrderController::class, 'showCheckout'])->name('client.checkout');
    Route::post('/checkout', [OrderController::class, 'store'])->name('client.checkout.store');
    Route::get('/checkout/success', [OrderController::class, 'success'])->name('client.checkout.success');
    Route::get('/order/verify/{token}', [OrderController::class, 'verifyEmail'])->name('client.order.verify');

    // Client auth (login / register) using existing controllers but client views
    Route::get('/login', [AuthController::class, 'showLogin'])->name('client.login');
    Route::post('/login', [AuthController::class, 'login'])->name('client.login.post');
    Route::get('/register', [RegisterController::class, 'show'])->name('client.register');
    Route::post('/register', [RegisterController::class, 'store'])->name('client.register.store');

    // Comment routes
    Route::get('/p/{product}/comments', [\App\Http\Controllers\Client\CommentController::class, 'index'])->name('client.comments.index');
    Route::post('/p/{product}/comments', [\App\Http\Controllers\Client\CommentController::class, 'store'])->name('client.comments.store');

    // Category routes
    Route::get('/danh-muc/{slug}', [\App\Http\Controllers\Client\CategoryController::class, 'show'])->name('client.category.show');
});

// Cart routes
Route::middleware(['web'])->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
    Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
});

// Registration routes (client-styled view)
Route::get('/register', [RegisterController::class, 'show'])->name('register');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

// Login / Logout routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin routes - Yêu cầu đăng nhập và quyền admin
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', fn () => view('admin.dashboard'))->name('dashboard');

    Route::resource('brands', \App\Http\Controllers\Admin\BrandController::class);
    Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class);

    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
    Route::resource('colors', \App\Http\Controllers\Admin\ColorController::class);
    Route::resource('storages', \App\Http\Controllers\Admin\StorageController::class);
    Route::resource('versions', \App\Http\Controllers\Admin\VersionController::class);

    // Products
    Route::get('/products',            [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/create',     [ProductController::class, 'create'])->name('products.create');
    Route::post('/products',           [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{id}',       [ProductController::class, 'show'])->name('products.show');
    Route::get('/products/{id}/edit',  [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{id}',       [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{id}',    [ProductController::class, 'destroy'])->name('products.destroy');

    // Product variants
    Route::prefix('products/{productId}/variants')->name('products.variants.')->group(function () {
        Route::get('/',                [ProductVariantController::class, 'index'])->name('index');
        Route::get('/create',          [ProductVariantController::class, 'create'])->name('create');
        Route::post('/',               [ProductVariantController::class, 'store'])->name('store');
        Route::get('/{variantId}/edit',[ProductVariantController::class, 'edit'])->name('edit');
        Route::put('/{variantId}',     [ProductVariantController::class, 'update'])->name('update');
        Route::delete('/{variantId}',  [ProductVariantController::class, 'destroy'])->name('destroy');
    });
});

