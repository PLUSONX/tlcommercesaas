<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
//       if (function_exists('tenancy') && tenancy()->initialized) {
//     \Log::info('Debugbar disabled for tenant: ' . tenant('id'));
//     \Debugbar::disable();
// } else {
//     \Log::info('Debugbar enabled (central domain)');
// }
    }
}
