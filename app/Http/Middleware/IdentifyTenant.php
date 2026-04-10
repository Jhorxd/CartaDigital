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

        // Si hay al menos un subdominio y no es www
        if (count($parts) >= 2 && $parts[0] !== 'www') {
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
