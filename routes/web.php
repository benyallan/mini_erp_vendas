<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ProductController::class, 'index'])->name('products.index');
Route::get('/product/create', [ProductController::class, 'create'])->name('products.create');
Route::post('/product', [ProductController::class, 'store'])->name('products.store');
Route::get('/product/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
Route::put('/product/{product}', [ProductController::class, 'update'])->name('products.update');
Route::delete('/product/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
Route::post('/cart/add', [ProductController::class, 'addToCart'])->name('cart.add');
Route::get('/checkout', [ProductController::class, 'checkout'])->name('checkout');
Route::post('/checkout', [ProductController::class, 'finalizeOrder'])->name('checkout.finalize');
Route::post('/webhook', [ProductController::class, 'webhook'])->name('webhook');
