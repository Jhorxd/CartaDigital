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

        \Log::info('IdentifyTenant: Checking host', ['host' => $host, 'parts' => $parts]);

        // Si hay al menos un subdominio y no es www (>= 3 para dominios como domain.com)
        if (count($parts) >= 3 && $parts[0] !== 'www') {
            $subdomain = $parts[0];
            
            $tenant = Tenant::where('subdomain', $subdomain)->where('is_active', true)->first();

            if ($tenant) {
                app()->instance('tenant_id', $tenant->id);
                $request->attributes->add(['tenant' => $tenant]);
                
                // Set default parameter for route() generation
                \Log::info('IdentifyTenant: Tenant identified', ['subdomain' => $subdomain, 'tenant_id' => $tenant->id]);
            } else {
                \Log::warning('IdentifyTenant: Tenant not found', ['subdomain' => $subdomain]);
                abort(404, 'Sitio web inactivo o no existe.');
            }
        }

        return $next($request);
    }
}
