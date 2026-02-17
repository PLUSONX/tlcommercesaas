<?php

namespace App\Providers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Log::info("inside boot() of appserviceprovider.");
        // Log::info("Tenant_working: " . tenant());
        // Log::info("Tenant: " . tenant('id'));
        // View::composer('*', function ($view) {
        //     Log::info("View Composer executing...");
        //     if (tenant()) {
        //         Log::info("Tenant ID: " . ($currentTenant->id ?? 'Not Identified Yet'));
        //         $facebook = DB::table('tl_com_social_media_integrations')
        //             ->where('provider', 'facebook_pixel')
        //             ->first();

        //         $view->with('facebook_integration', $facebook);
        //     }
        // });

//       if (function_exists('tenancy') && tenancy()->initialized) {
//     \Log::info('Debugbar disabled for tenant: ' . tenant('id'));
//     \Debugbar::disable();
// } else {
//     \Log::info('Debugbar enabled (central domain)');
// }
    }
}
