<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        $isTenant = app()->has('tenant_id');
        $isSecretRoute = request()->is('acceso-total-151418*');

        // Bloqueo de seguridad: No permitir el login equivocado en el dominio equivocado
        if ($isTenant && $isSecretRoute) abort(404);
        if (!$isTenant && !$isSecretRoute) abort(404);

        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Redirección inteligente post-login
        if (app()->has('tenant_id')) {
            // Construimos la URL absoluta usando el host actual para evitar perder el subdominio
            $adminUrl = request()->getScheme() . '://' . request()->getHost() . '/admin/dashboard';
            return redirect()->intended($adminUrl);
        }

        // El Super Admin va a /dashboard
        return redirect()->intended(route('dashboard'));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
