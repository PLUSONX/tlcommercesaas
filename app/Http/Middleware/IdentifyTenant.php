<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IdentifyTenant
{
    public function handle(Request $request, Closure $next)
    {
        // For production, get tenant from subdomain
        if (!app()->environment('local')) {
            $host = $request->getHost(); // e.g., mystore.tlcommerce.xyz
            $tenant = explode('.', $host)[0];
        } 
        // For local development, get tenant from query string
        else {
            $tenant = $request->query('tenant', null); // e.g., ?tenant=kfc
        }

        if ($tenant) {
            // Store the tenant somewhere globally
            // Example: singleton in container
            app()->instance('currentTenant', $tenant);

            // Optionally: make it available in views
            view()->share('currentTenant', $tenant);
        }

        return $next($request);
    }
}
