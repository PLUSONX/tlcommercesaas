<?php

namespace App\Providers;

use Composer\Autoload\ClassLoader;
use Illuminate\Support\ServiceProvider;

class PluginServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register() {}

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
         // Always load Saas plugin for tenant routes
        // if (tenancy()->initialized) {
            // $this->loadSaasPluginForTenant();
        // }

        $this->loadSaasPlugin();

        if (env('IS_USER_REGISTERED') == 1) {
            $plugins = getActivePlugins(true);
            foreach ($plugins as $plugin) {
                $this->configurePlugin($plugin);
            }
        }
    }

    /**
     * Configure plugin
     */
    public function configurePlugin($plugin)
    {
        //Merge config
        $has_config = file_exists(base_path('plugins/' . $plugin->location . '/config/config.php'));
        if ($has_config) {
            $this->mergeConfigFrom(base_path('plugins/' . $plugin->location . '/config/config.php'), $plugin->location);
        }
        //Load helper
        $has_helpers = file_exists(base_path('plugins/' . $plugin->location . '/helpers/helpers.php'));
        if ($has_helpers) {
            require_once(base_path('plugins/' . $plugin->location . '/helpers/helpers.php'));
        }
        //Load view
        $this->loadViewsFrom(base_path('plugins/' . $plugin->location . '/views'), 'plugin/' . $plugin->location);

        //Generate Namespace
        $loader = new ClassLoader;
        $loader->setPsr4($plugin->namespace, base_path('plugins/' . $plugin->location . '/src'));
        $loader->register(true);
    }


        /**
     * Load Saas plugin specifically for tenant context
     */
       protected function loadSaasPlugin()
    {
        $saasLocation = 'saas';
        
        // Load config
        $configPath = base_path('plugins/' . $saasLocation . '/config/config.php');
        if (file_exists($configPath)) {
            $this->mergeConfigFrom($configPath, $saasLocation);
        }
        
        // Load helpers
        $helpersPath = base_path('plugins/' . $saasLocation . '/helpers/helpers.php');
        if (file_exists($helpersPath)) {
            require_once($helpersPath);
        }
        
        // Load views
        $this->loadViewsFrom(base_path('plugins/' . $saasLocation . '/views'), 'plugin/' . $saasLocation);
        
        // Register namespace
        $loader = new ClassLoader;
        $loader->setPsr4('Plugin\\Saas\\', base_path('plugins/' . $saasLocation . '/src'));
        $loader->register(true);
    }
}
