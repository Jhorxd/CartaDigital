<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CartaController;
use App\Http\Controllers\Admin\TenantController;
use Illuminate\Support\Facades\Route;

$domain = env('APP_DOMAIN', 'localhost');

// 1. Rutas de Subdominio (Carta Pública y Admin del Tenant)
Route::domain('{tenant}.' . $domain)->group(function () {
    
    // Carta Pública
    Route::get('/', [CartaController::class, 'index'])->name('carta.index');

    // Panel Administrativo del Tenant
    Route::middleware(['auth', 'verified'])->prefix('admin')->name('tenant.admin.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Tenant\DashboardController::class, 'index'])->name('dashboard');
        
        Route::resource('categories', \App\Http\Controllers\Tenant\CategoryController::class);
        Route::resource('products', \App\Http\Controllers\Tenant\ProductController::class);
        
        Route::get('/settings', [\App\Http\Controllers\Tenant\SettingsController::class, 'edit'])->name('settings.edit');
        Route::patch('/settings', [\App\Http\Controllers\Tenant\SettingsController::class, 'update'])->name('settings.update');
    });
});

// 2. Rutas Globales / Dominio Base (Super Admin, Landing y Auth)
// Nota: La autenticación es global para evitar problemas con parámetros de ruta.
// El middleware IdentifyTenant (global) se encarga de identificar al restaurante si existe.

// 2. Rutas Globales / Dominio Base (Super Admin, Landing y Auth)
Route::domain($domain)->group(function () {
    Route::get('/', function () {
        if (auth()->check()) {
            return redirect()->route('dashboard');
        }
        return view('welcome');
    });

    // Super Admin (Solo accesible en micartadig.com)
    Route::middleware(['auth', 'verified'])->group(function () {
        Route::get('/dashboard', [TenantController::class, 'index'])->name('dashboard');
        Route::resource('tenants', TenantController::class);
    });

    // Perfil de Usuario (Solo accesible en micartadig.com)
    Route::middleware('auth')->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });
});

// Rutas de Autenticación Unificadas (Cargadas globalmente, pero controladas por dominio internamente)
require __DIR__.'/auth.php';
