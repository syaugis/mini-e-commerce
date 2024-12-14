<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*------------------------------------------
--------------------------------------------
All Authentication Routes List
--------------------------------------------
--------------------------------------------*/

Route::controller(AuthController::class)->group(function () {
    Route::get('/', 'showLogin')->name('login');
    Route::post('login',  'login')->name('login.submit');
    Route::get('register',  'showRegister')->name('register');
    Route::post('register',  'register')->name('register.submit');
    Route::post('logout',  'logout')->name('logout');
});

/*------------------------------------------
--------------------------------------------
All Admin Routes List
--------------------------------------------
--------------------------------------------*/
Route::middleware('auth', 'role:ADMIN')->prefix('admin')->group(function () {
    Route::get('dashboard', [HomeController::class, 'index'])->name('admin.dashboard');

    Route::controller(ProductController::class)->prefix('product')->group(function () {
        Route::get('', 'index')->name('admin.product.index');
        Route::get('add', 'create')->name('admin.product.create');
        Route::post('add', 'store')->name('admin.product.store');
        Route::get('edit/{id}', 'edit')->name('admin.product.edit');
        Route::put('edit/{id}', 'update')->name('admin.product.update');
        Route::delete('destroy/{id}', 'destroy')->name('admin.product.destroy');
    });

    Route::controller(ProductCategoryController::class)->prefix('category')->group(function () {
        Route::get('', 'index')->name('admin.category.index');
        Route::get('add', 'create')->name('admin.category.create');
        Route::post('add', 'store')->name('admin.category.store');
        Route::get('edit/{id}', 'edit')->name('admin.category.edit');
        Route::put('edit/{id}', 'update')->name('admin.category.update');
        Route::delete('destroy/{id}', 'destroy')->name('admin.category.destroy');
    });

    Route::controller(OrderController::class)->prefix('order')->group(function () {
        Route::get('', 'index')->name('admin.order.index');
        Route::get('show/{id}', 'show')->name('admin.order.show');
        Route::put('edit/{id}', 'update')->name('admin.order.update');
    });

    Route::controller(UserController::class)->prefix('user')->group(function () {
        Route::get('', 'index')->name('admin.user.index');
        Route::get('show/{id}', 'show')->name('admin.user.show');
        Route::get('{id}/orders', 'getOrders')->name('admin.user.orders');
        Route::get('{id}/shipping-address', 'getShippingAddresses')->name('admin.user.shipping-addresses');
    });
});
