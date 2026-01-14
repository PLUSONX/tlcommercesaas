<?php

namespace Core\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;


class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     *
     * @return void
     */
    public function boot()
    {
        //  $this->routes(function () {
            
        //     // Load core routes WITHOUT prefix and WITHOUT IS_USER_REGISTERED check
        //     // These are superadmin routes and should always be available
        //     Route::middleware('web')->group(base_path('core/routes/core.php'));
            
        //     // Load API routes
        //     Route::middleware('api')->prefix('api')->group(base_path('core/routes/api.php'));
            
        //     // Optional: Keep the old logic if needed for other routes
        //     if (env('IS_USER_REGISTERED') == 1) {
        //         $middlewares = routeApplicableMiddlewares();
        //         // Any other conditional routes can go here
        //     }
        // });
        $this->routes(function () {
            if (env('IS_USER_REGISTERED') == 1) {
                $middlewares = routeApplicableMiddlewares();

                 Route::middleware($middlewares['web'])
                        ->prefix(getSuperAdminPrefix())
                        ->group(base_path('Core/routes/core.php'));

                    Route::middleware($middlewares['web'])
                        ->prefix(getSaasPrefix())
                        ->group(base_path('plugins/saas/routes/user.php'));

                    //  Route::middleware($middlewares['web'])
                    //     ->prefix(getAdminPrefix())
                    //     ->group(base_path('Core/routes/core.php'));
                    
                    Route::middleware($middlewares['api'])
                        ->prefix('api')
                        ->group(base_path('Core/routes/api.php'));

                        $middlewares = routeApplicableMiddlewares();
                        $active_theme = getActiveTheme();

                        if (file_exists(base_path('themes/' . $active_theme->location . '/routes/api.php'))) {
                           
                            Route::middleware($middlewares['api'])->prefix('api')->group(base_path('themes/' . $active_theme->location . '/routes/api.php'));
                        }

                        if (file_exists(base_path('themes/'  . $active_theme->location . '/routes/web.php'))) {
                          
                            Route::middleware($middlewares['web'])->group(base_path('themes/' . $active_theme->location . '/routes/web.php'));
                        }

                // Route::middleware($middlewares['web'])->prefix(config('app.superadmin_prefix', 'superadmin'))->group(base_path('Core/routes/core.php'));
                // Core routes WITHOUT prefix (superadmin routes)
                // Route::middleware($middlewares['web'])->group(base_path('Core/routes/core.php'));        

                // Route::middleware($middlewares['web'])->prefix(getAdminPrefix())->group(base_path('Core/routes/core.php'));
                // Route::middleware($middlewares['api'])->prefix('api')->group(base_path('Core/routes/api.php'));

                $host = request()->getHost();

                $centralDomains = config('tenancy.central_domains', ['localhost']);

                $isCentralDomain = in_array($host, $centralDomains);

                $path = request()->path();


                // echo "<script>console.log('host:', " . json_encode($host) . ");</script>";

                // echo "<script>console.log('centralDomains:', " . json_encode($centralDomains) . ");</script>";

                // echo "<script>console.log('isCentralDomain:', " . json_encode($isCentralDomain) . ");</script>";

                // echo "<script>console.log('path:', " . json_encode($path) . ");</script>";


                // if ($isCentralDomain == false) {
                //     Route::middleware($middlewares['web'])
                //         ->group(base_path('routes/tenant.php'));
                // } 
                // else {
                    // Load core routes only on central domain
                   
                // }
            }
        });
    }
}
