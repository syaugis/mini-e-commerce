<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ShippingAddressController;
use App\Http\Controllers\PaymentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*------------------------------------------
--------------------------------------------
All Authentication Routes List
--------------------------------------------
--------------------------------------------*/

Route::post('/payments/midtrans/callback', [PaymentController::class, 'midtransCallback']);

Route::controller(AuthController::class)->prefix('auth')->group(function () {
    Route::post('register', 'register');
    Route::post('login', 'login');
});

Route::middleware(['auth:sanctum', 'role:USER'])->group(function () {
    Route::controller(ShippingAddressController::class)->prefix('shipping-addresses')->group(function () {
        //    index
        Route::get('', 'index'); //show
        Route::post('', 'store');
        Route::put('{id}', 'update');
        // Route::delete('{id}', 'destroy');
    });

    // Route::apiResource('user', UserController::class);

    Route::get('products', [ProductController::class, 'index']);

    Route::controller(CartController::class)->prefix('cart')->group(function () {
        Route::get('', 'getCart');

        Route::post('items', 'addItem');
        Route::put('items/{productId}', 'updateItemQuantity');
        Route::delete('items/{productId}', 'removeItem');
        Route::delete('items', 'clearCart');
    });

    Route::controller(OrderController::class)->prefix('order')->group(function () {
        Route::post('checkout',  'checkout');
        Route::post('callback', 'callback')->name('callback');
        Route::post('show',  'show');
    });

    Route::post('auth/logout', [AuthController::class, 'logout']);
});
