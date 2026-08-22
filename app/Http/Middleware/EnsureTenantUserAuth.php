<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureTenantUserAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (tenancy()->initialized && Auth::check()) {
            $userId = Auth::id();
            
            // Check if this user exists in the tenant database
            $tenantUser = \Core\Models\User::on('tenant')->find($userId);
            
            if (!$tenantUser) {
                // User doesn't exist in tenant database - logout
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                
                toastNotification('error', translate("Please login with a valid store account"));
                return redirect()->away($this->centralLoginUrl());
                // return redirect()->route('core.login');
            }
            
            // Re-authenticate with tenant user
            $tenantUser->setConnection('tenant');
            Auth::setUser($tenantUser);
            session()->put('password_hash_' . Auth::getDefaultDriver(), $tenantUser->password);
        }
        
        return $next($request);
    }

    private function centralLoginUrl(): string
    {
        $scheme = request()->isSecure() ? 'https' : 'http';
        $port = request()->getPort();

        if (app()->environment('production')) {
            $parsedCentralUrl = parse_url((string) config('app.url', ''));
            $parsedCentralUrl = is_array($parsedCentralUrl) ? $parsedCentralUrl : [];
            $centralDomain = $parsedCentralUrl['host'] ?? null;

            if ($centralDomain) {
                $scheme = $parsedCentralUrl['scheme'] ?? $scheme;
                $port = $parsedCentralUrl['port'] ?? null;
            } else {
                $centralDomains = config('tenancy.central_domains', []);
                $centralDomain = $centralDomains[0] ?? 'localhost';
            }
        } else {
            $centralDomain = config('tenancy.local_central_domain', '127.0.0.1');
        }

        $portSuffix = ($port !== null && (($scheme === 'http' && $port != 80) || ($scheme === 'https' && $port != 443)))
            ? ':' . $port
            : '';

        $adminPrefix = trim(getAdminPrefix(), '/');

        return $scheme . '://' . $centralDomain . $portSuffix . '/' . $adminPrefix . '/login';
    }
    // public function handle(Request $request, Closure $next)
    // {
    //     if (tenancy()->initialized && Auth::check()) {
    //         $userId = Auth::id();
            
    //         // Check if this user exists in the tenant database
    //         $tenantUser = \Core\Models\User::on('tenant')->find($userId);
            
    //         if (!$tenantUser) {
    //             // User doesn't exist in tenant database - logout
    //             Auth::logout();
    //             $request->session()->invalidate();
    //             $request->session()->regenerateToken();
                
    //             toastNotification('error', translate("Please login with a valid store account"));
    //             return redirect()->away('http://127.0.0.1:8000/admin/login');
    //             // return redirect()->route('core.login');
    //         }
            
    //         // Re-authenticate with tenant user
    //         $tenantUser->setConnection('tenant');
    //         Auth::setUser($tenantUser);
    //     }
        
    //     return $next($request);
    // }
}