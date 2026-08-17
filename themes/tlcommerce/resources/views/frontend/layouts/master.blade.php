

@php
    use Plugin\TlcommerceCore\Repositories\SettingsRepository;
    use Core\Repositories\SettingsRepository as CoreSettingRepository;
    use Theme\TLCommerce\Repositories\LayoutSettingsRepository;
    use Theme\TLCommerce\Repositories\ThemeOptionRepository;
    use Theme\TLCommerce\Repositories\ProductListViewRepository;
    use Theme\TLCommerce\Http\Controllers\Api\HomePageController;
    use Theme\TLCommerce\Http\Controllers\Api\SliderController;
    use Plugin\TlcommerceCore\Http\Controllers\LayoutSettingsController;
    use Core\Models\Language;
    use Plugin\TlcommerceCore\Models\Currency;
    use Illuminate\Support\Facades\Cache;
    use Illuminate\Support\Facades\Log;

    $siteProperties = Cache::rememberForever(tenantCacheKey('site-properties'), function () {
        return CoreSettingRepository::SiteProperties();
    });

    // Instant layout bootstrap for Vue (cached per tenant)
    $activeLayoutBootstrap = (new LayoutSettingsRepository())->getActiveLayout();
    $productListViewBootstrap = (new ProductListViewRepository())->getStorefrontSettings();

    $site_name = str_replace('"', '', $siteProperties['site_title']);
    $site_name = str_replace("'", '', $site_name);

    $site_moto = $siteProperties['site_motto'] != null ? '|' . $siteProperties['site_motto'] : '';
    $site_title = $site_name . '' . $site_moto;

    $default_language = defaultLanguage();
    $default_curency = SettingsRepository::defaultCurrency();
    $default_currency_json = $default_curency ? $default_curency->toJson() : null;

    $active_theme = getActiveTheme();

    $body_typography = themeOptionToCss('body_typography', $active_theme->id);
    $paragraph_typography = themeOptionToCss('paragraph_typography', $active_theme->id);
    $heading_typography = themeOptionToCss('heading_typography', $active_theme->id);
    $menu_typography = themeOptionToCss('menu_typography', $active_theme->id);
    $button_typography = themeOptionToCss('button_typography', $active_theme->id);
    $logo_details = getGeneralSettingsDetails();
    $custom_js_properties = getThemeOption('custom_js', $active_theme->id);
    $site_mood_setting = getThemeOption('dark_light_switcher', $active_theme->id);
    $site_default_mood =
        isset($site_mood_setting['site_default_screen_mood']) &&
        $site_mood_setting['site_default_screen_mood'] == 'dark'
            ? 'dark'
            : '';

    $theme_color = getThemeOption('theme_color', $active_theme->id);
    $theme_primary_color = resolveThemePrimaryColor($theme_color);

    $pixelIntegrations = Cache::rememberForever(tenantCacheKey('social-pixel-integrations'), function () {
        return DB::table('tl_com_social_media_integrations')
            ->whereIn('provider', ['facebook_pixel', 'tiktok_pixel', 'snapchat_pixel'])
            ->get()
            ->keyBy('provider');
    });

    $facebook_integration = data_get($pixelIntegrations, 'facebook_pixel');
    $tiktok_integration = data_get($pixelIntegrations, 'tiktok_pixel');
    $snapchat_integration = data_get($pixelIntegrations, 'snapchat_pixel');

    $fbPixelId = null;
    if ($facebook_integration && $facebook_integration->is_active) {
        $fbSettings = json_decode($facebook_integration->settings, true);
        $fbPixelId = $fbSettings['pixel_id'] ?? null;
    }

    $tiktokPixelId = null;
    if ($tiktok_integration && $tiktok_integration->is_active) {
        $tiktokSettings = json_decode($tiktok_integration->settings, true);
        $tiktokPixelId = $tiktokSettings['pixel_id'] ?? null;
    }

    $snapchatPixelId = null;
    if ($snapchat_integration && $snapchat_integration->is_active) {
        $snapchatSettings = json_decode($snapchat_integration->settings, true);
        $snapchatPixelId = $snapchatSettings['pixel_id'] ?? null;
    }

    $bootstrapLanguages = Cache::rememberForever(tenantCacheKey('-active-languages'), function () {
        return Language::where('status', config('settings.general_status.active'))
            ->select('id', 'native_name as title', 'code')
            ->get();
    });

    $bootstrapCurrencies = Cache::rememberForever(tenantCacheKey('active-currencies'), function () {
        return Currency::where('status', config('settings.general_status.active'))
            ->select('id', 'name', 'code', 'symbol', 'conversion_rate', 'position', 'thousand_separator', 'decimal_separator', 'number_of_decimal')
            ->get();
    });

    $bootstrapSiteSettings = Cache::rememberForever(tenantCacheKey('e-commerce-settings'), function () {
        return SettingsRepository::siteSettings();
    });

    $tlcAssetVersionPath = base_path('themes/tlcommerce/asset-version.json');
    $assetVersion = '1';
    if (is_readable($tlcAssetVersionPath)) {
        $tlcAssetVersionData = json_decode(file_get_contents($tlcAssetVersionPath), true);
        if (!empty($tlcAssetVersionData['version'])) {
            $assetVersion = (string) $tlcAssetVersionData['version'];
        }
    }

    (new ThemeOptionRepository())->ensureTenantCssBundle();

    $homeSlidersBootstrap = null;
    $homeSectionsBootstrap = null;
    $menusBootstrap = null;
    $splitScreenListBootstrap = null;
    $isHomePath = request()->is('/') || request()->path() === '' || request()->path() === '/';

    if ($isHomePath) {
        try {
            $homeSlidersBootstrap = app(SliderController::class)->slidersPayload();
        } catch (\Throwable $e) {
            Log::warning('Home sliders bootstrap failed: ' . $e->getMessage());
        }

        try {
            $homeSectionsBootstrap = app(HomePageController::class)->homePageSectionsPayload();
        } catch (\Throwable $e) {
            Log::warning('Home sections bootstrap failed: ' . $e->getMessage());
        }

        try {
            $menusBootstrap = app(LayoutSettingsController::class)->menusPayload();
        } catch (\Throwable $e) {
            Log::warning('Home menus bootstrap failed: ' . $e->getMessage());
        }

        $layoutType = is_array($activeLayoutBootstrap) ? ($activeLayoutBootstrap['type'] ?? null) : null;
        $isFeaturePane = in_array($layoutType, ['split_screen', 'modern'], true);
        $listViewOn = !empty($productListViewBootstrap['is_list_view_enabled']);

        if ($isFeaturePane && $listViewOn) {
            try {
                $splitScreenListBootstrap = [
                    'success' => true,
                    'data' => (new ProductListViewRepository())->getSplitScreenProductList(),
                ];
            } catch (\Throwable $e) {
                Log::warning('Split-screen product list bootstrap failed: ' . $e->getMessage());
            }
        }
    }
    
    
@endphp

@php
    // Force browsers / proxies to revalidate the HTML shell so new main.js?v= is picked up
    if (!headers_sent()) {
        header('Cache-Control: no-cache, no-store, must-revalidate, max-age=0');
        header('Pragma: no-cache');
        header('Expires: 0');
    }
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="tlc-booting">

<head>
    {{-- Force browsers / Instagram WebView to revalidate HTML so they pick up new ?v= assets --}}
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />

    @if ($fbPixelId)
        <script>
            !function(f,b,e,v,n,t,s)
            {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
            n.callMethod.apply(n,arguments):n.queue.push(arguments)};
            if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
            n.queue=[];t=b.createElement(e);t.async=!0;
            t.src=v;s=b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t,s)}(window, document,'script',
            'https://connect.facebook.net/en_US/fbevents.js');
            fbq('init', '{{ $fbPixelId }}');
            fbq('set', 'autoConfig', false, '{{ $fbPixelId }}');
            try {
                if (!sessionStorage.getItem('tlc_pixel_pageview')) {
                    fbq('track', 'PageView');
                    sessionStorage.setItem('tlc_pixel_pageview', '1');
                }
            } catch (e) {
                fbq('track', 'PageView');
            }
        </script>
        <noscript>
            <img height="1" width="1" style="display:none"
                 src="https://www.facebook.com/tr?id={{ $fbPixelId }}&ev=PageView&noscript=1"/>
        </noscript>
    @endif

    @if ($tiktokPixelId)
        <script>
            !function (w, d, t) {
                w.TiktokAnalyticsObject=t;var ttq=w[t]=w[t]||[];
                ttq.methods=["page","track","identify","instances","debug","on","off","once","ready","alias","group","enableCookie","disableCookie"];
                ttq.setAndDefer=function(t,e){t[e]=function(){t.push([e].concat(Array.prototype.slice.call(arguments,0)))}};
                for(var i=0;i<ttq.methods.length;i++)ttq.setAndDefer(ttq,ttq.methods[i]);
                ttq.instance=function(t){for(var e=ttq._i[t]||[],n=0;n<ttq.methods.length;n++)ttq.setAndDefer(e,ttq.methods[n]);return e};
                ttq.load=function(e,n){var i="https://analytics.tiktok.com/i18n/pixel/events.js";
                ttq._i=ttq._i||{};ttq._i[e]=[];ttq._i[e]._u=i;ttq._t=ttq._t||{};ttq._t[e]=+new Date;
                ttq._o=ttq._o||{};ttq._o[e]=n||{};
                var o=document.createElement("script");o.type="text/javascript";o.async=!0;o.src=i+"?sdkid="+e+"&lib="+t;
                var a=document.getElementsByTagName("script")[0];a.parentNode.insertBefore(o,a)};
                ttq.load('{{ $tiktokPixelId }}');
                ttq.page();
            }(window, document, 'ttq');
        </script>
    @endif

    @if ($snapchatPixelId)
        <script>
            (function(e,t,n){if(e.snaptr)return;var a=e.snaptr=function()
            {a.handleRequest?a.handleRequest.apply(a,arguments):a.queue.push(arguments)};
            a.queue=[];var s='script';r=t.createElement(s);r.async=!0;
            r.src=n;var u=t.getElementsByTagName(s)[0];
            u.parentNode.insertBefore(r,u);})(window,document,
            'https://sc-static.net/scevent.min.js');
            snaptr('init', '{{ $snapchatPixelId }}');
            snaptr('track', 'PAGE_VIEW');
        </script>
    @endif

    @if (isActivePluging('tlecommercecore'))
        {{-- Download in parallel with CSS; runs after parse, after the pixel snippets above --}}
        <script defer src="{{ asset('themes/tlcommerce/js/main.js?v=' . $assetVersion) }}"></script>
    @endif

    <meta charset="utf-8">
    @if (isset($logo_details['favicon']))
        <link rel="shortcut icon" href="{{ project_asset($logo_details['favicon']) }}">
    @else
        <link rel="shortcut icon" href="{{ asset('backend/assets/img/favicon.png') }}">
        <!-- <link rel="shortcut icon" href="{{ asset('/public/backend/assets/img/favicon.png') }}"> -->
    @endif

    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">

    <!-- <meta name="viewport" content="width=device-width, initial-scale=1"> -->

    @yield('seo')
    <meta property="og:image:width" content="1200" />
    <meta name="brand_name" content="{{ $site_name }}" />
    <link rel="canonical" href="{{ env('APP_URL') }}" />
    <meta property="og:url" content="{{ env('APP_URL') }}" />
    <meta name="twitter:domain" content="{{ env('APP_URL') }}" />
    <meta property="og:site_name" content="{{ $site_name }}" />
    <meta name="twitter:site" content="{{ $site_name }}" />
    <meta name="apple-mobile-web-app-title" content="{{ $site_title }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    {{-- Material Icons: before main.js so split-screen toolbar ligatures are not visible text on first paint --}}
    <link rel="preload"
        href="https://fonts.gstatic.com/s/materialicons/v126/flUhRq6tzZclQEJ-Vdg-IuiaDsNc.woff2"
        as="font" type="font/woff2" crossorigin>
    <link rel="stylesheet" href="{{ asset('themes/tlcommerce/css/google-icons.css') }}?v={{ $assetVersion }}">
    <!-- <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700;900&display=swap"
        rel="stylesheet"> -->
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,200..800&display=swap"
    rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('themes/tlcommerce/css/vendor/bootstrap.css') }}?v={{ $assetVersion }}">
    <link rel="stylesheet" href="{{ asset('themes/tlcommerce/css/vendor/coreui.min.css') }}?v={{ $assetVersion }}">
    <link rel="stylesheet" href="{{ asset('themes/tlcommerce/css/vendor/vue-select.css') }}?v={{ $assetVersion }}">
    <link rel="stylesheet" href="{{ asset('themes/tlcommerce/css/vendor/toast-sugar.css') }}?v={{ $assetVersion }}">
    <link rel="stylesheet" href="{{ asset('themes/tlcommerce/css/app.css') }}?v={{ $assetVersion }}">

    <link rel="stylesheet" href="{{ asset('backend/assets/plugins/fontawsome/css/all.min.css') }}?v={{ $assetVersion }}">
    <link rel="stylesheet" href="{{ asset('themes/default/public/assets/css/custom_app.css') }}?v={{ $assetVersion }}">
    <!-- <link rel="stylesheet" href="{{ asset('/public/backend/assets/plugins/fontawsome/css/all.min.css') }}">
    <link rel="stylesheet" href="/themes/tlcommerce/public/blog/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{ asset('/themes/tlcommerce/public/css/custom_app.css') }}"> -->

    <style>:root {
        --mainC: {{ $theme_primary_color }};
        --color-primary: {{ $theme_primary_color }};
    }</style>
    <style>
        html.tlc-booting #app {
            display: none;
        }
        #tlc-boot-loader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: #f0f8ff;
            z-index: 99999;
            opacity: 1;
            transition: opacity 0.3s ease;
            contain: strict;
            transform: translateZ(0);
            will-change: opacity;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        #tlc-boot-loader.is-done {
            opacity: 0;
            pointer-events: none;
        }
        #tlc-boot-loader .loader {
            --color-1: #fff;
            --color-2: var(--mainC, #e62d04);
            --size: 1px;

            width: calc(54 * var(--size));
            height: calc(54 * var(--size));
            position: relative;
            border-radius: calc(4 * var(--size));
            background-color: var(--color-1);
            background-image:
                radial-gradient(circle calc(5 * var(--size)), var(--color-2) 100%, transparent 0),
                radial-gradient(circle calc(5 * var(--size)), var(--color-2) 100%, transparent 0),
                radial-gradient(circle calc(5 * var(--size)), var(--color-2) 100%, transparent 0),
                radial-gradient(circle calc(5 * var(--size)), var(--color-2) 100%, transparent 0),
                radial-gradient(circle calc(5 * var(--size)), var(--color-2) 100%, transparent 0),
                radial-gradient(circle calc(5 * var(--size)), var(--color-2) 100%, transparent 0);
            background-repeat: no-repeat;
            animation:
                tlc-boot-move 4s linear infinite,
                tlc-boot-rotate 2s linear infinite;
        }
        @keyframes tlc-boot-rotate {
            0%,
            20% {
                transform: rotate(0deg);
            }
            30%,
            40% {
                transform: rotate(90deg);
            }
            50%,
            60% {
                transform: rotate(180deg);
            }
            70%,
            80% {
                transform: rotate(270deg);
            }
            90%,
            100% {
                transform: rotate(360deg);
            }
        }
        @keyframes tlc-boot-move {
            0%,
            9% {
                background-position:
                    calc(-12 * var(--size)) calc(-15 * var(--size)),
                    calc(-12 * var(--size)) 0,
                    calc(-12 * var(--size)) calc(15 * var(--size)),
                    calc(12 * var(--size)) calc(-15 * var(--size)),
                    calc(12 * var(--size)) 0,
                    calc(12 * var(--size)) calc(15 * var(--size));
            }
            10%,
            25% {
                background-position:
                    0 calc(-15 * var(--size)),
                    calc(-12 * var(--size)) 0,
                    calc(-12 * var(--size)) calc(15 * var(--size)),
                    calc(34 * var(--size)) calc(-15 * var(--size)),
                    calc(12 * var(--size)) 0,
                    calc(12 * var(--size)) calc(15 * var(--size));
            }
            30%,
            45% {
                background-position:
                    0 calc(-34 * var(--size)),
                    calc(-12 * var(--size)) calc(-10 * var(--size)),
                    calc(-12 * var(--size)) calc(12 * var(--size)),
                    calc(34 * var(--size)) calc(-15 * var(--size)),
                    calc(12 * var(--size)) calc(-10 * var(--size)),
                    calc(12 * var(--size)) calc(12 * var(--size));
            }
            50%,
            65% {
                background-position:
                    0 calc(-34 * var(--size)),
                    calc(-12 * var(--size)) calc(-34 * var(--size)),
                    calc(-12 * var(--size)) calc(12 * var(--size)),
                    calc(34 * var(--size)) calc(-12 * var(--size)),
                    0 calc(-10 * var(--size)),
                    calc(12 * var(--size)) calc(12 * var(--size));
            }
            70%,
            85% {
                background-position:
                    0 calc(-34 * var(--size)),
                    calc(-12 * var(--size)) calc(-34 * var(--size)),
                    0 calc(12 * var(--size)),
                    calc(34 * var(--size)) calc(-12 * var(--size)),
                    0 calc(-10 * var(--size)),
                    calc(34 * var(--size)) calc(12 * var(--size));
            }
            90%,
            100% {
                background-position:
                    0 calc(-34 * var(--size)),
                    calc(-12 * var(--size)) calc(-34 * var(--size)),
                    0 0,
                    calc(34 * var(--size)) calc(-12 * var(--size)),
                    0 0,
                    calc(34 * var(--size)) calc(12 * var(--size));
            }
        }
    </style>

    <link rel="stylesheet" type="text/css" href="{{ asset(tenantCssRelativePath('tenant-bundle.css')) }}?v={{ $assetVersion }}">
    <!-- Including all google fonts link -->
    @includeIf('theme/tlcommerce::frontend.blog.includes.custom.google-font-link', [
        'body_typography' => $body_typography,
        'paragraph_typography' => $paragraph_typography,
        'heading_typography' => $heading_typography,
        'menu_typography' => $menu_typography,
        'button_typography' => $button_typography,
    ])
    <!-- Including all dynamic css -->
    @includeIf('theme/tlcommerce::frontend.blog.includes.custom.tl-dynamic-css', [
        'body_typography' => $body_typography,
        'paragraph_typography' => $paragraph_typography,
        'heading_typography' => $heading_typography,
        'menu_typography' => $menu_typography,
        'button_typography' => $button_typography,
    ])
    <!-- Theme Option Css -->

    {{-- Include Builder Css If Page or Homepage is Build with Builder --}}
    @yield('builder-css-link')
    <!--Custom script-->
    @if ($custom_js_properties != null)
        {!! $custom_js_properties['header_custom_js_code'] !!}
    @endif
    <!--End custom script-->

    <style>
        *:not(.material-icons):not([class*="fa-"]):not([class*="ti-"]) {
            font-family: 'Bricolage Grotesque', sans-serif !important;
        }
        #tlc-boot-loader, #tlc-boot-loader * {
            font-family: sans-serif !important;
        }
    </style>
</head>

<body class="antialiased">
    <div id="tlc-boot-loader" role="status" aria-live="polite" aria-busy="true" aria-label="Loading">
        <div class="loader"></div>
    </div>
    <script>
        (function () {
            var hideTimer = null;
            window.__TLC_HIDE_BOOT_LOADER__ = function () {
                if (window.__TLC_BOOT_LOADER_HIDDEN__) {
                    return;
                }
                window.__TLC_BOOT_LOADER_HIDDEN__ = true;
                if (hideTimer) {
                    clearTimeout(hideTimer);
                    hideTimer = null;
                }
                document.documentElement.classList.remove('tlc-booting');
                var el = document.getElementById('tlc-boot-loader');
                if (!el) {
                    return;
                }
                el.setAttribute('aria-busy', 'false');
                var fade = function () {
                    el.classList.add('is-done');
                    setTimeout(function () {
                        if (el && el.parentNode) {
                            el.parentNode.removeChild(el);
                        }
                    }, 300);
                };
                if (typeof requestAnimationFrame === 'function') {
                    requestAnimationFrame(function () {
                        requestAnimationFrame(fade);
                    });
                } else {
                    setTimeout(fade, 50);
                }
            };
            hideTimer = setTimeout(function () {
                if (typeof window.__TLC_HIDE_BOOT_LOADER__ === 'function') {
                    window.__TLC_HIDE_BOOT_LOADER__();
                }
            }, 30000);
        })();
    </script>
    <div id="app">
    </div>
    <script>
        try {
            //set site title
            localStorage.setItem('site_title', @json($site_title));

            //set default language
            if (localStorage.getItem('locale') == null) {
                localStorage.setItem('locale', @json($default_language));
            }

            //set selected / default currency (JSON-safe; never echo Eloquent model raw)
            @if ($default_currency_json)
                if (localStorage.getItem('currency') == null) {
                    localStorage.setItem('currency', @json($default_currency_json));
                }
                localStorage.setItem('default_currency', @json($default_currency_json));
            @endif

            //set default mood
            localStorage.setItem('mode', @json($site_default_mood));
        } catch (e) {
            // Instagram / private WebViews may block storage — do not break page boot
            console.warn('[tlcommerce] localStorage seed failed', e);
        }
    </script>
    <!--Custom script-->
    @if ($custom_js_properties != null)
        {!! $custom_js_properties['footer_custom_js_code'] !!}
    @endif
    <!--End custom script-->

    @if (isActivePluging('tlecommercecore'))

    {{-- Best-effort store_visit once per browser session (never blocks shell) --}}
    <script>
        (function () {
            var STORE_VISIT_KEY = 'tlc_analytics_store_visit';

            function trackStoreVisit() {
                try {
                    if (window.sessionStorage && sessionStorage.getItem(STORE_VISIT_KEY) === '1') {
                        return;
                    }

                    var url = '/api/v1/ecommerce-core/analytics/track';
                    var body = JSON.stringify({ event_type: 'store_visit', product_id: null });
                    var sent = false;

                    if (navigator.sendBeacon) {
                        try {
                            var blob = new Blob([body], { type: 'application/json' });
                            sent = !!navigator.sendBeacon(url, blob);
                        } catch (e) {}
                    }

                    if (!sent) {
                        fetch(url, {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                            body: body,
                            keepalive: true,
                            credentials: 'same-origin'
                        }).catch(function () {});
                        sent = true;
                    }

                    if (sent && window.sessionStorage) {
                        try {
                            sessionStorage.setItem(STORE_VISIT_KEY, '1');
                        } catch (e) {}
                    }
                } catch (e) {}
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', trackStoreVisit);
            } else {
                trackStoreVisit();
            }
        })();
    </script>

    <script>
        window.__TLC_BOOTSTRAP__ = {
            activeLayout: @json($activeLayoutBootstrap),
            productListViewSettings: @json($productListViewBootstrap),
            assetVersion: @json($assetVersion),
            siteProperties: @json($siteProperties),
            themePrimaryColor: @json($theme_primary_color),
            languages: @json($bootstrapLanguages),
            currencies: @json($bootstrapCurrencies),
            siteSettings: @json($bootstrapSiteSettings),
            sliders: @json($homeSlidersBootstrap),
            homePageSections: @json($homeSectionsBootstrap),
            menus: @json($menusBootstrap),
            splitScreenProductList: @json($splitScreenListBootstrap),
        };
    </script>

    @endif


</body>

</html>
