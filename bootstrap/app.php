<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trustProxies(at: '*');

        $middleware->web(append: [
            \App\Http\Middleware\IdentifyTenant::class,
        ]);

        $middleware->alias([
            'tenant' => \App\Http\Middleware\IdentifyTenant::class,
            'block.tenant' => \App\Http\Middleware\BlockTenantAccess::class,
        ]);

        // Redirección inteligente basada en el dominio
        $middleware->redirectTo(function (Illuminate\Http\Request $request) {
            $host = $request->getHost();
            $parts = explode('.', $host);
            $isLocalhost = str_ends_with($host, 'localhost') || str_ends_with($host, '127.0.0.1');
            $minParts = $isLocalhost ? 2 : 3;

            // Si es dominio principal, mandamos al login secreto
            if (count($parts) < $minParts || $parts[0] === 'www') {
                return route('login.admin');
            }
            // Si es subdominio, mandamos al login normal del restaurante
            return route('login', ['tenant' => $parts[0]]);
        });

        // Redirección para usuarios YA autenticados que intentan entrar a login
        $middleware->redirectUsersTo(function (Illuminate\Http\Request $request) {
            $host = $request->getHost();
            $parts = explode('.', $host);
            $isLocalhost = str_ends_with($host, 'localhost') || str_ends_with($host, '127.0.0.1');
            $minParts = $isLocalhost ? 2 : 3;

            // Si es dominio principal, mandamos al dashboard global (Super Admin)
            if (count($parts) < $minParts || $parts[0] === 'www') {
                return route('dashboard');
            }
            
            // Si es un tenant (subdominio), mandamos a su panel de administración
            return route('tenant.admin.dashboard');
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
