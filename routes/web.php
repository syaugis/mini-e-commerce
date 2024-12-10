<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
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

    Route::get('product')->name('admin.product.index');
    Route::get('category')->name('admin.category.index');
});
