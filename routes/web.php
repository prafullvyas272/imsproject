<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Department\DepartmentController;
use App\Http\Controllers\Role\RoleController;
use App\Http\Controllers\User\UserController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\UserActivity\UserActivityController;

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


Route::middleware(['auth', AdminMiddleware::class])->group(function () {

    // Roles routes here
    Route::resource('role', RoleController::class);

    // User routes here
    Route::resource('user', UserController::class);

    // Department routes here
    Route::resource('department', DepartmentController::class);

    // User activity routes here
    Route::get('user/{user}/activity' , [UserActivityController::class, 'showUserActivity'])->name('user.showUserActivity');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
