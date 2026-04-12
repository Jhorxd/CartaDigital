<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use App\Models\Tenant;

class IdentifyTenant
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $host = $request->getHost();
        $parts = explode('.', $host);


        // Detectamos si es un subdominio (En localhost bastan 2 partes, en producción 3)
        $isLocalhost = str_ends_with($host, 'localhost') || str_ends_with($host, '127.0.0.1');
        $minParts = $isLocalhost ? 2 : 3;

        if (count($parts) >= $minParts && $parts[0] !== 'www') {
            $subdomain = $parts[0];
            
            $tenant = Tenant::where('subdomain', $subdomain)->where('is_active', true)->first();

            if ($tenant) {
                app()->instance('tenant_id', $tenant->id);
                $request->attributes->add(['tenant' => $tenant]);
                
                // Set default parameter for route() generation
                \Illuminate\Support\Facades\URL::defaults(['tenant' => $subdomain]);
            } else {
                abort(404, 'Sitio web inactivo o no existe.');
            }
        }

        return $next($request);
    }
}
