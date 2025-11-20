<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductVariantController;

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
    
    // Comment routes
    Route::get('/p/{product}/comments', [\App\Http\Controllers\Client\CommentController::class, 'index'])->name('client.comments.index');
    Route::post('/p/{product}/comments', [\App\Http\Controllers\Client\CommentController::class, 'store'])->name('client.comments.store');
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

<<<<<<< HEAD
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
=======
// Registration routes (show client-styled view)
>>>>>>> ce7f9d05044b5923566136beda1ee9cb8285c6bf
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

<<<<<<< HEAD
    Route::resource('colors', \App\Http\Controllers\Admin\ColorController::class);

    Route::resource('storages', \App\Http\Controllers\Admin\StorageController::class);

    Route::resource('versions', \App\Http\Controllers\Admin\VersionController::class);
=======
    // Products routes
    Route::get('/products',            [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/create',     [ProductController::class, 'create'])->name('products.create');
    Route::post('/products',           [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{id}',       [ProductController::class, 'show'])->name('products.show');
    Route::get('/products/{id}/edit',  [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{id}',       [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{id}',    [ProductController::class, 'destroy'])->name('products.destroy');
>>>>>>> ce7f9d05044b5923566136beda1ee9cb8285c6bf
});


