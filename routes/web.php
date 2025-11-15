<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\AuthController;

// Route::get('/', function () {
//     return view('home');
// });

Route::get('/', function () { return redirect()->route('client.index'); });

// Electro frontend routes (Client)
Route::prefix('client')->group(function () {
    Route::get('/', [\App\Http\Controllers\Client\ProductController::class, 'index'])->name('client.index');
    Route::get('/p/{product}', [\App\Http\Controllers\Client\ProductController::class, 'show'])->name('client.product.show');
    Route::get('/store', function () {
        return view('electro.store');
    })->name('client.store');
    Route::get('/checkout', function () {
        return view('electro.checkout');
    })->name('client.checkout');
    // Client auth (login / register) using existing controllers but client views
    Route::get('/login', [\App\Http\Controllers\AuthController::class, 'showLogin'])->name('client.login');
    Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login'])->name('client.login.post');

    Route::get('/register', [\App\Http\Controllers\Auth\RegisterController::class, 'show'])->name('client.register');
    Route::post('/register', [\App\Http\Controllers\Auth\RegisterController::class, 'store'])->name('client.register.store');
});

use App\Http\Controllers\Client\CartController;

Route::middleware(['web'])->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
    Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
});

// Electro frontend routes (Client)
Route::prefix('client')->group(function () {
    Route::get('/', [\App\Http\Controllers\Client\ProductController::class, 'index'])->name('client.index');
    Route::get('/p/{product}', [\App\Http\Controllers\Client\ProductController::class, 'show'])->name('client.product.show');
    Route::get('/store', function () {
        return view('electro.store');
    })->name('client.store');
    Route::get('/checkout', function () {
        return view('electro.checkout');
    })->name('client.checkout');
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

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        return view('admin.dashboard');
    })->name('dashboard');
    
    Route::resource('brands', \App\Http\Controllers\Admin\BrandController::class);

    Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class);

    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);

    // Products routes
    Route::get('/products',            [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/create',     [ProductController::class, 'create'])->name('products.create');
    Route::post('/products',           [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{id}',       [ProductController::class, 'show'])->name('products.show');
    Route::get('/products/{id}/edit',  [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{id}',       [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{id}',    [ProductController::class, 'destroy'])->name('products.destroy');
});


