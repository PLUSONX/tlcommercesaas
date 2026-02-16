<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HandleTenantLoginToken
{
    public function handle($request, Closure $next)
    {
        $host = request()->getHost();

        $centralDomains = config('tenancy.central_domains', ['localhost']);

        $isCentralDomain = in_array($host, $centralDomains);
        // Only process if we have a login token and tenancy is initialized
        if ($request->has('login_token') && !Auth::check() && !$isCentralDomain) {

            // $token = $request->get('login_token');
            $token = $request->input('login_token');
            $hashedToken = hash('sha256', $token);

            try {

                $tokenData = DB::connection('mysql')->table('tenant_login_tokens')
                    ->where('token', $hashedToken)
                    ->first();

                if ($tokenData) {
                    // Token is valid - get the tenant and initialize it
                    $tenant = \App\Models\Tenant::find($tokenData->tenant_id);

                    if ($tenant) {
                        // Manually initialize tenancy
                        tenancy()->initialize($tenant);

                        // Now find user in the tenant database
                        $user = \Core\Models\User::on('tenant')
                            ->where('email', $tokenData->email)
                            ->first();

                        if ($user) {
                            $user->setConnection('tenant');
                            Auth::login($user);

                            // Delete the used token
                            DB::connection('mysql')->table('tenant_login_tokens')
                                ->where('token', $hashedToken)
                                ->delete();
                        }
                    }
                }
            } catch (\Exception $e) {
                \Log::error('Token login error: ' . $e->getMessage());
                return redirect('/admin/login')->withErrors(['error' => 'Login failed']);
            }
        }

        return $next($request);
    }
    // public function handle($request, Closure $next)
    // {
    //     $host = request()->getHost();

    //     $centralDomains = config('tenancy.central_domains', ['localhost']);

    //     $isCentralDomain = in_array($host, $centralDomains);
    //     // Only process if we have a login token and tenancy is initialized
    //     if ($request->has('login_token') && !Auth::check() && !$isCentralDomain) {

    //         // dd($request);

    //         $token = $request->get('login_token');
    //         $hashedToken = hash('sha256', $token);

    //         // dd($hashedToken);

    //         try {
    //             // Look up token in central database
    //             // $tokenData = DB::connection('mysql')->table('tenant_login_tokens')
    //             //     ->where('token', $hashedToken)
    //             //     ->where('expires_at', '>', now())
    //             //     ->first();

    //             $tokenData = DB::connection('mysql')->table('tenant_login_tokens')
    //                 ->where('token', $hashedToken)
    //                 ->first();

    //             // dd($tokenData);
    //             // dd(tenancy()->initialized);

    //             if ($tokenData) {
    //                 // Token is valid - get the tenant and initialize it
    //                 $tenant = \App\Models\Tenant::find($tokenData->tenant_id);

    //                 // dd($tenant);

    //                 if ($tenant) {
    //                     // Manually initialize tenancy
    //                     tenancy()->initialize($tenant);

    //                     // dd(tenancy()->initialized);

    //                     // Now find user in the tenant database
    //                     $user = \Core\Models\User::on('tenant')
    //                         ->where('email', $tokenData->email)
    //                         ->first();

    //                     if ($user) {
    //                         $user->setConnection('tenant');
    //                         Auth::login($user);

    //                         // Delete the used token
    //                         DB::connection('mysql')->table('tenant_login_tokens')
    //                             ->where('token', $hashedToken)
    //                             ->delete();

    //                         // dd($user);

    //                         // Redirect to clean URL without token
    //                         // return redirect('/admin/dashboard');
    //                     }
    //                 }
    //             }
    //             // Invalid or expired token - redirect to login
    //             // DB::connection('mysql')->table('tenant_login_tokens')
    //             //     ->where('token', $hashedToken)
    //             //     ->delete();

    //             // return redirect('/admin/login')->withErrors(['error' => 'Invalid or expired login token']);
    //         } catch (\Exception $e) {
    //             \Log::error('Token login error: ' . $e->getMessage());
    //             return redirect('/admin/login')->withErrors(['error' => 'Login failed']);
    //         }
    //     }

    //     return $next($request);
    // }
    // public function handle($request, Closure $next)
    // {
    //     if ($request->has('login_token') && !Auth::check()) {
    //         $token = $request->get('login_token');
    //         $hashedToken = hash('sha256', $token);

    //         // Look up token in central database
    //         $tokenData = DB::connection('mysql')->table('tenant_login_tokens')
    //             ->where('token', $hashedToken)
    //             ->where('expires_at', '>', now())
    //             ->first();

    //         if ($tokenData && tenancy()->initialized) {
    //             $currentTenant = tenant();

    //             // Verify token is for current tenant
    //             if ($tokenData->tenant_id == $currentTenant->id) {
    //                 // Find user in tenant database
    //                 $user = \Core\Models\User::on('tenant')
    //                     ->where('email', $tokenData->email)
    //                     ->first();

    //                 if ($user) {
    //                     $user->setConnection('tenant');
    //                     Auth::login($user);

    //                     // Delete the used token
    //                     DB::connection('mysql')->table('tenant_login_tokens')
    //                         ->where('token', $hashedToken)
    //                         ->delete();

    //                     // Redirect to clean URL without token
    //                     return redirect('/admin/dashboard');
    //                 }
    //             }
    //         }

    //         // Invalid or expired token
    //         if ($request->has('login_token')) {
    //             return redirect('/admin/login')->with('error', 'Invalid or expired login token');
    //         }
    //     }

    //     return $next($request);
    // }
}
