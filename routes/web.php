<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
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
    Route::get('/products/{id}/edit',  [ProductController::class, 'edit'])->name('admin.products.edit');
    Route::put('/products/{id}',       [ProductController::class, 'update'])->name('admin.products.update');
    Route::delete('/products/{id}',    [ProductController::class, 'destroy'])->name('admin.products.destroy');
});
