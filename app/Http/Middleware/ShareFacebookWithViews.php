<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ShareFacebookWithViews
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        // By the time this runs, Tenancy middleware has already finished!
        if (tenant()) {
            Log::info("Middleware sharing pixel for Tenant: " . tenant('id'));

            $facebook = DB::table('tl_com_social_media_integrations')
                ->where('provider', 'facebook_pixel')
                ->first();

            Log::info("facebook: " . json_encode($facebook));


            // Share this variable with ALL views globally
            View::share('facebook_integration', $facebook);
        }

        return $next($request);
    }
}