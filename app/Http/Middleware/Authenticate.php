<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        echo "<script>console.log('request:', " . json_encode($request) . ");</script>";

            if (! $request->expectsJson()) {

                if ($request->is('admin/*')) {
                    return route('core.login'); // Tenant Admin
                } else {
                    return route('subscriber.login'); // Store user / subscriber
                }

            // if (! $request->expectsJson()) {
            //     // return route('subscriber.login');
            //     // return route('core.login');
            //     if ($request->is('superadmin/*')) {
            //     return route('core.login'); // Superadmin
            // } elseif ($request->is('admin/*')) {
            //     return route('tenant.login'); // Tenant Admin
            // } else {
            //     return route('subscriber.login'); // Store user / subscriber
            // }
        }
    }
}
