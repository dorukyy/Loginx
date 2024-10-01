<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\MailActivationController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use Illuminate\Support\Facades\Route;

//Login Routes
Route::get('login', [LoginController::class, 'loginPage'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

//Register Routes
Route::get('register', [RegisterController::class, 'registerPage'])->name('register');
Route::post('register', [RegisterController::class, 'register']);

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
