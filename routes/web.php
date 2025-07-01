<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// 2FA Routes
Route::get('verify-code/{email}', [AuthenticatedSessionController::class, 'showVerifyCodePage'])->name('show-verify-code-page');
Route::post('verify-code', [AuthenticatedSessionController::class, 'verifyCode'])->name('verify-code');
Route::post('resend-code', [AuthenticatedSessionController::class, 'resendCode'])->name('resend-code');
Route::post('enable-two-fa', [RegisteredUserController::class, 'enableTwoFA'])->name('enableTwoFA');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
