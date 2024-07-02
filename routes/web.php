<?php

use Illuminate\Support\Facades\Route;



// Route::get('/', function () {
//     return view('index');
// });

Route::get('/', [App\Http\Controllers\ProductController::class, 'index'])->name('home');
Route::get('/products', [App\Http\Controllers\ProductController::class, 'products'])->name('products');
Route::get('/single_product/{product}', [App\Http\Controllers\ProductController::class, 'show'])->name('show.product');
Route::get('/cart', [App\Http\Controllers\CartController::class, 'cart'])->name('cart');
Route::post('addToCart', [App\Http\Controllers\CartController::class, 'addToCart'])->name('addToCart');

Route::post('removeFromCart', [App\Http\Controllers\CartController::class, 'removeFromCart'])->name('removeFromCart');
Route::post('editFromCart', [App\Http\Controllers\CartController::class, 'editFromCart'])->name('editFromCart');

Route::get('checkout', [App\Http\Controllers\CartController::class, 'checkout'])->name('checkout');
Route::post('placeorder', [App\Http\Controllers\CartController::class, 'placeorder'])->name('placeorder');

Route::get('payment', [App\Http\Controllers\PaymentController::class, 'payment'])->name('payment');