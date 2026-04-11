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
            $parts = explode('.', $request->getHost());
            // Si es dominio principal, mandamos al login secreto
            if (count($parts) < 3 || $parts[0] === 'www') {
                return route('login.admin');
            }
            // Si es subdominio, mandamos al login normal del restaurante
            return route('login', ['tenant' => $parts[0]]);
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
