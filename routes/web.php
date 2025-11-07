<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('brands', \App\Http\Controllers\Admin\BrandController::class);
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
});

