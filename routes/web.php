<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

Route::prefix('admin')->group(function () {
    Route::resource('brands', \App\Http\Controllers\Admin\BrandController::class);
    Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class);
});

