<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use Illuminate\Support\Facades\Route;

//Login Routes

Route::controller(LoginController::class)
    ->name('login.')
    ->group(function () {
        Route::get('login', 'loginPage')->name('login-page')->middleware('guest');
        Route::post('login', 'login')->name('login')->middleware('guest');
        Route::get('logout','logout')->name('logout')->middleware('auth');

    });

Route::controller(RegisterController::class)
    ->middleware('guest')
    ->name('register.')
    ->group(function () {
        Route::get('register', 'registerPage')->name('register-page');
        Route::post('register', 'register')->name('register');

    });
