<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BlockTenantAccess
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Si el sistema ha detectado un inquilino (subdominio), bloqueamos el acceso
        if (app()->has('tenant_id')) {
            abort(404);
        }

        return $next($request);
    }
}
