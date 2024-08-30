<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\MailActivationController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use Illuminate\Support\Facades\Route;

//Login Routes

Route::controller(LoginController::class)
    ->name('login.')
    ->group(function () {
        Route::get('login', 'loginPage')->name('login-page')->middleware('guest');
        Route::post('login', 'login')->name('login')->middleware('guest');
        Route::get('logout', 'logout')->name('logout')->middleware('auth');

    });

Route::controller(RegisterController::class)
    ->middleware('guest')
    ->name('register.')
    ->group(function () {
        Route::get('register', 'registerPage')->name('register-page');
        Route::post('register', 'register')->name('register');

    });

Route::controller(ForgotPasswordController::class)
    ->middleware('guest')
    ->name('forgot-password.')
    ->group(function () {
        Route::get('forgot-password', 'showResetForm')->name('show-reset-form');
        Route::get('forgot-password/reset', 'reset')->name('reset');
        Route::post('forgot-password', 'sendMail')->name('sendMail');
        Route::post('forgot-password/reset', 'setNewPassword')->name('setNewPassword');
    });


Route::controller(MailActivationController::class)
    ->name('activation.')
    ->prefix('activation')
    ->group(function () {
        Route::get('resend', 'showResendActivationMailForm')->name('resend');
        Route::post('resend', 'resendActivationMail')->name('resend');
        Route::get('activate', 'activate')->name('activate');
    });
