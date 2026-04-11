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
        ]);

        // Redirección inteligente basada en el dominio base
        $middleware->redirectTo(function (Illuminate\Http\Request $request) {
            $host = $request->getHost();
            $domain = env('APP_DOMAIN', 'localhost');

            // Si es exactamente el dominio principal o www
            if ($host === $domain || $host === 'www.' . $domain) {
                return route('login.admin');
            }
            
            // Si estamos en un subdominio (.micartadig.com)
            return route('login');
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
