<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ProductController::class, 'index'])->name('products.index');
Route::get('/product/create', [ProductController::class, 'create'])->name('products.create');
Route::post('/product', [ProductController::class, 'store'])->name('products.store');
Route::get('/product/{id}/edit', [ProductController::class, 'edit']);
Route::put('/product/{id}', [ProductController::class, 'update']);
Route::post('/cart/add', [ProductController::class, 'addToCart']);
Route::get('/checkout', [ProductController::class, 'checkout']);
Route::post('/checkout', [ProductController::class, 'finalizeOrder']);
Route::post('/webhook', [ProductController::class, 'webhook']);
