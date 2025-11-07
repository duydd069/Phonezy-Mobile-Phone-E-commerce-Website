<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('home');
});


// Registration routes
Route::get('/register', [RegisterController::class, 'show'])->name('register');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

// Login / Logout routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('brands', \App\Http\Controllers\Admin\BrandController::class);

    Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class);

    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
});

