<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('home');
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

Route::prefix('admin')->group(function () {
    Route::resource('brands', \App\Http\Controllers\Admin\BrandController::class);
    // Route::resource('products', \App\Http\Controllers\Admin\ProductController::class);
});

use App\Http\Controllers\Admin\ProductController;

// TẠM THỜI bỏ middleware auth để test
Route::prefix('admin')->group(function () {
    Route::get('/products',            [ProductController::class, 'index'])->name('admin.products.index');
    Route::get('/products/create',     [ProductController::class, 'create'])->name('admin.products.create');
    Route::post('/products',           [ProductController::class, 'store'])->name('admin.products.store');
    Route::get('/products/{id}',       [ProductController::class, 'show'])->name('admin.products.show');
    Route::get('/products/{id}/edit',  [ProductController::class, 'edit'])->name('admin.products.edit');
    Route::put('/products/{id}',       [ProductController::class, 'update'])->name('admin.products.update');
    Route::delete('/products/{id}',    [ProductController::class, 'destroy'])->name('admin.products.destroy');
});


// Registration routes
Route::get('/register', [RegisterController::class, 'show'])->name('register');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

// Login / Logout routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('brands', \App\Http\Controllers\Admin\BrandController::class);

    Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class);

    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
});


