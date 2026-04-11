<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CartaController;
use App\Http\Controllers\Admin\TenantController;
use Illuminate\Support\Facades\Route;

$host = request()->getHost();
$parts = explode('.', $host);
// Si tiene subdominio (ej: elorigen.micartadig.com), el dominio base son los últimos dos segmentos
$domain = (count($parts) >= 3) ? ($parts[$count-2] . '.' . $parts[$count-1]) : env('APP_DOMAIN', 'localhost');

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

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return view('welcome');
});

// Super Admin (Accesible solo si NO estamos en un subdominio)
Route::middleware(['auth', 'verified', 'block.tenant'])->group(function () {
    Route::get('/dashboard', [TenantController::class, 'index'])->name('dashboard');
    Route::resource('tenants', TenantController::class);
});

// Perfil de Usuario
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Rutas de Autenticación Unificadas (Login, Logout, etc.)
require __DIR__.'/auth.php';
