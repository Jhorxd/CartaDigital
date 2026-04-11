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

Route::get('/test-auth', function () {
    return [
        'is_authenticated' => auth()->check(),
        'user' => auth()->user(),
        'session_id' => session()->getId(),
        'session_data' => session()->all(), // Ver qué hay guardado
        'app_has_tenant_id' => app()->has('tenant_id'),
        'tenant_id_value' => app()->has('tenant_id') ? app('tenant_id') : 'NOT SET',
        'app_domain' => env('APP_DOMAIN'),
        'request_host' => request()->getHost(),
    ];
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

// Super Admin
Route::middleware(['auth', 'verified'])->group(function () {
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
