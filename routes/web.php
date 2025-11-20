<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductVariantController;

Route::get('/', function () {
    return view('home');
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

// TẠM THỜI bỏ middleware auth để test
Route::prefix('admin')->group(function () {
    Route::get('/products',            [ProductController::class, 'index'])->name('admin.products.index');
    Route::get('/products/create',     [ProductController::class, 'create'])->name('admin.products.create');
    Route::post('/products',           [ProductController::class, 'store'])->name('admin.products.store');
    Route::get('/products/{id}',       [ProductController::class, 'show'])->name('admin.products.show');
    Route::get('/products/{id}/edit',  [ProductController::class, 'edit'])->name('admin.products.edit');
    Route::put('/products/{id}',       [ProductController::class, 'update'])->name('admin.products.update');
    Route::delete('/products/{id}',    [ProductController::class, 'destroy'])->name('admin.products.destroy');

    Route::prefix('products/{productId}/variants')->name('admin.products.variants.')->group(function () {
        Route::get('/',                [ProductVariantController::class, 'index'])->name('index');
        Route::get('/create',          [ProductVariantController::class, 'create'])->name('create');
        Route::post('/',               [ProductVariantController::class, 'store'])->name('store');
        Route::get('/{variantId}/edit',[ProductVariantController::class, 'edit'])->name('edit');
        Route::put('/{variantId}',     [ProductVariantController::class, 'update'])->name('update');
        Route::delete('/{variantId}',  [ProductVariantController::class, 'destroy'])->name('destroy');
    });
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

    Route::resource('colors', \App\Http\Controllers\Admin\ColorController::class);

    Route::resource('storages', \App\Http\Controllers\Admin\StorageController::class);

    Route::resource('versions', \App\Http\Controllers\Admin\VersionController::class);
});


