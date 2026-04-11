<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
/*
    Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('register');

    Route::post('register', [RegisteredUserController::class, 'store']);
*/

    // URL secreta para el SuperAdmin (funciona en cualquier lugar, pero la usarás en el dominio principal)
    Route::get('acceso-total-151418', [AuthenticatedSessionController::class, 'create']);
    Route::post('acceso-total-151418', [AuthenticatedSessionController::class, 'store']);

    // URL normal de login (solo permitida para restaurantes/subdominios)
    Route::get('login', function (Illuminate\Http\Request $request) {
        $parts = explode('.', $request->getHost());
        // Si estamos en el dominio principal (ej. micartadig.com sin subdominio) bloqueamos el acceso
        if (count($parts) < 3 || $parts[0] === 'www') {
            abort(404);
        }
        // Si estamos en un restaurante (ej. elorigen.micartadig.com) mostramos el login
        return app()->call([AuthenticatedSessionController::class, 'create']);
    })->name('login');

    Route::post('login', function (Illuminate\Http\Request $request) {
        $parts = explode('.', $request->getHost());
        if (count($parts) < 3 || $parts[0] === 'www') {
            abort(404);
        }
        return app()->call([AuthenticatedSessionController::class, 'store']);
    });

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store');
});

Route::middleware('auth')->group(function () {
    Route::get('verify-email', EmailVerificationPromptController::class)
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});
