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
        $domain = env('APP_DOMAIN', 'localhost');

        // Si el host no es exactamente el dominio principal (ignora www)
        if ($host !== $domain && $host !== 'www.' . $domain) {
            
            // Verificamos si el host termina en .dominio.com para extraer el subdominio
            if (str_ends_with($host, '.' . $domain)) {
                $subdomain = str_replace('.' . $domain, '', $host);
                
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
        }

        return $next($request);
    }
}
