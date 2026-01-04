<?php

namespace App\Http\Middleware;

use Closure;
use Session;
use Illuminate\Http\Request;

class SetRequestLanguage
{
    public function handle(Request $request, Closure $next)
    {
        $locale = 'en'; // Default fallback

        if ($request->hasHeader('Accept-Language')) {
            $acceptLanguage = $request->header('Accept-Language');
            
            // Parse the Accept-Language header to get the primary locale
            // Example: "en_US,en;q=0.9" -> "en_US"
            $locale = $this->parseAcceptLanguage($acceptLanguage);
        } elseif (env('DEFAULT_LANGUAGE') != null) {
            $locale = env('DEFAULT_LANGUAGE');
        }

        // Ensure locale is valid before setting
        try {
            app()->setLocale($locale);
            Session::put('api_locale', $locale);
        } catch (\Exception $e) {
            // Fallback to 'en' if locale is invalid
            app()->setLocale('en');
            Session::put('api_locale', 'en');
        }

        return $next($request);
    }

    /**
     * Parse Accept-Language header and return the primary locale
     *
     * @param string $acceptLanguage
     * @return string
     */
    private function parseAcceptLanguage($acceptLanguage)
    {
        // Split by comma to get all languages
        $languages = explode(',', $acceptLanguage);
        
        if (empty($languages)) {
            return 'en';
        }

        // Get the first language (highest priority)
        $primaryLanguage = trim($languages[0]);
        
        // Remove quality value if present (e.g., "en;q=0.9" -> "en")
        if (strpos($primaryLanguage, ';') !== false) {
            $primaryLanguage = trim(explode(';', $primaryLanguage)[0]);
        }

        // Convert locale format: "en-US" or "en_US" -> "en"
        // Laravel typically uses two-letter codes
        if (strpos($primaryLanguage, '-') !== false) {
            $primaryLanguage = explode('-', $primaryLanguage)[0];
        } elseif (strpos($primaryLanguage, '_') !== false) {
            $primaryLanguage = explode('_', $primaryLanguage)[0];
        }

        // Ensure it's a valid format (only letters)
        if (!preg_match('/^[a-z]{2,3}$/i', $primaryLanguage)) {
            return 'en';
        }

        return strtolower($primaryLanguage);
    }
    // public function handle(Request $request, Closure $next)
    // {
        
    //     if ($request->hasHeader('Accept-Language')) {
    //         $locale = $request->header('Accept-Language');
    //     } elseif (env('DEFAULT_LANGUAGE') != null) {
    //         $locale = env('DEFAULT_LANGUAGE');
    //     } else {
    //         $locale = 'en';
    //     }

    //     app()->setLocale($locale);
    //     Session::put('api_locale', $locale);
    //     return $next($request);
    // }
}
