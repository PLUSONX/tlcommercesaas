<?php

namespace Core\Providers;

use Illuminate\Support\ServiceProvider;
use Core\Services\PushNotificationService;

class CoreServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //

        
        // dd([
        //     'file_exists' => file_exists(base_path('core/Services/PushNotificationService.php')),
        //     'class_exists' => class_exists(PushNotificationService::class),
        //     'realpath' => realpath(base_path('core')),
        // ]);
    
            $this->app->singleton(PushNotificationService::class, function ($app) {
                return new PushNotificationService();
    });

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(base_path('Core/Views'), 'core');
        // $this->loadRoutesFrom(base_path('Core/routes/core.php'));

    }
}
