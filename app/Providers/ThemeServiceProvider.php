<?php

namespace App\Providers;

use Illuminate\Support\Facades\Log;
use Composer\Autoload\ClassLoader;
use Illuminate\Support\ServiceProvider;

class ThemeServiceProvider extends ServiceProvider
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
//     public function boot()
// {
//     // Get active theme early
//     $active_theme = getActiveTheme();

//     if (!$active_theme) {
//         return;
//     }

//     $themeLocation = $active_theme->location; // e.g. "default"
//     $themeBasePath = base_path("themes/{$themeLocation}");

//     /*
//     |--------------------------------------------------------------------------
//     | Merge Theme Config (Optional)
//     |--------------------------------------------------------------------------
//     */
//     $configPath = "{$themeBasePath}/config/config.php";
//     if (file_exists($configPath)) {
//         $this->mergeConfigFrom($configPath, $themeLocation);
//     }

//     /*
//     |--------------------------------------------------------------------------
//     | Load Theme Helpers (Optional)
//     |--------------------------------------------------------------------------
//     */
//     $helpersPath = "{$themeBasePath}/helpers/helpers.php";
//     if (file_exists($helpersPath)) {
//         require_once $helpersPath;
//     }

//     /*
//     |--------------------------------------------------------------------------
//     | Register PSR-4 Namespace (Safe)
//     |--------------------------------------------------------------------------
//     */
//     if (!empty($active_theme->namespace)) {
//         $namespace = rtrim($active_theme->namespace, '\\') . '\\';

//         $srcPath = "{$themeBasePath}/src";
//         if (is_dir($srcPath)) {
//             $loader = new ClassLoader;
//             $loader->setPsr4($namespace, $srcPath);
//             $loader->register(true);
//         }
//     }

//     /*
//     |--------------------------------------------------------------------------
//     | Register Theme Views (CRITICAL)
//     |--------------------------------------------------------------------------
//     */
//     $viewsPath = "{$themeBasePath}/resources/views";
//     if (is_dir($viewsPath)) {
//         $this->loadViewsFrom($viewsPath, "theme.{$themeLocation}");
//     }
// }
//     public function boot()
// {
//     $active_theme = getActiveTheme();

//     // dd($active_theme);

//     if (!$active_theme) {
//         return;
//     }

//     // Merge config (optional)
//     if (file_exists(base_path("themes/{$active_theme->location}/config/config.php"))) {
//         $this->mergeConfigFrom(
//             base_path("themes/{$active_theme->location}/config/config.php"),
//             $active_theme->location
//         );
//     }

//     // Load helpers (optional)
//     if (file_exists(base_path("themes/{$active_theme->location}/helpers/helpers.php"))) {
//         require_once base_path("themes/{$active_theme->location}/helpers/helpers.php");
//     }

//     // Register PSR-4 namespace
//     $loader = new \Composer\Autoload\ClassLoader;
//     $loader->setPsr4(
//         $active_theme->namespace,
//         base_path("themes/{$active_theme->location}/src")
//     );
//     $loader->register(true);

//     // ✅ ALWAYS load views
//    $this->loadViewsFrom(
//     base_path("themes/{$active_theme->location}/resources/views"),
//     'theme.default'
// );
// }

    public function boot()
    {

    Log::info('----inside themeServiceProvider ----');

        // if (env('IS_USER_REGISTERED') == 1) {
            $active_theme = getActiveTheme();

            Log::info('themeServiceProvider settings', [
                'active_theme' => json_encode($active_theme),
            ]);

            //Merge config
            $has_config = file_exists(base_path('themes/' . $active_theme->location .  '/config/config.php'));
            if ($has_config) {
                $this->mergeConfigFrom(base_path('themes/' . $active_theme->location .  '/config/config.php'), $active_theme->location);
            }

            //Load helper functions
            $has_helpers = file_exists(base_path('themes/' . $active_theme->location . '/helpers/helpers.php'));
            if ($has_helpers) {
                require_once(base_path('themes/' . $active_theme->location . '/helpers/helpers.php'));
            }

            //Generate namespace
            $loader = new ClassLoader;
            $loader->setPsr4($active_theme->namespace, base_path('themes/' . $active_theme->location . '/src'));
            $loader->register(true);
            //Load view
            $this->loadViewsFrom(base_path('themes/' . $active_theme->location . '/resources/views'), 'theme/' . $active_theme->location);
        // }
    }
}
