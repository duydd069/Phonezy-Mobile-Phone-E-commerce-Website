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


// Registration routes (show client-styled view)
Route::get('/register', [RegisterController::class, 'show'])->name('register');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

// Login / Logout routes (show client-styled view)
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        return view('admin.dashboard');
    })->name('dashboard');
    
    Route::resource('brands', \App\Http\Controllers\Admin\BrandController::class);

    Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class);

    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
});


