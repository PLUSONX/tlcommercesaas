

@php
    use Plugin\TlcommerceCore\Repositories\SettingsRepository;
    use Core\Repositories\SettingsRepository as CoreSettingRepository;
    use Theme\TLCommerce\Repositories\LayoutSettingsRepository;
    use Illuminate\Support\Facades\Cache;
    use Illuminate\Support\Facades\Log;

    $siteProperties = Cache::rememberForever(tenantCacheKey('site-properties'), function () {
        return CoreSettingRepository::SiteProperties();
    });

    // Instant layout bootstrap for Vue (cached per tenant)
    $activeLayoutBootstrap = (new LayoutSettingsRepository())->getActiveLayout();

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

    $facebook_integration = DB::table('tl_com_social_media_integrations')
        ->where('provider', 'facebook_pixel')
        ->first();

    $tiktok_integration = DB::table('tl_com_social_media_integrations')
        ->where('provider', 'tiktok_pixel')
        ->first();

    $snapchat_integration = DB::table('tl_com_social_media_integrations')
        ->where('provider', 'snapchat_pixel')
        ->first();
    
    
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
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    {{-- Force browsers / Instagram WebView to revalidate HTML so they pick up new ?v= assets --}}
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />

    @if($facebook_integration && $facebook_integration->is_active)
    @php 
        $fbSettings = json_decode($facebook_integration->settings, true);
        $fbPixelId = $fbSettings['pixel_id'] ?? null;

    @endphp

    @if($fbPixelId)
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

            fbq('track', 'PageView');
            // window.fbq = fbq;
        </script>
        <noscript>
            <img height="1" width="1" style="display:none"
                 src="https://www.facebook.com/tr?id={{ $fbPixelId }}&ev=PageView&noscript=1"/>
        </noscript>
    @endif
@endif

    @if($tiktok_integration && $tiktok_integration->is_active)
    @php
        $tiktokSettings = json_decode($tiktok_integration->settings, true);
        $tiktokPixelId = $tiktokSettings['pixel_id'] ?? null;
    @endphp

    @if($tiktokPixelId)
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
    @endif

    @if($snapchat_integration && $snapchat_integration->is_active)
    @php
        $snapchatSettings = json_decode($snapchat_integration->settings, true);
        $snapchatPixelId = $snapchatSettings['pixel_id'] ?? null;
    @endphp

    @if($snapchatPixelId)
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
    <!-- <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700;900&display=swap"
        rel="stylesheet"> -->
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,200..800&display=swap"
    rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('backend/assets/plugins/fontawsome/css/all.min.css') }}">
    <link rel="stylesheet" href=" {{ asset('themes/default/public/assets/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('themes/default/public/assets/css/custom_app.css') }}">
    <!-- <link rel="stylesheet" href="{{ asset('/public/backend/assets/plugins/fontawsome/css/all.min.css') }}">
    <link rel="stylesheet" href="/themes/tlcommerce/public/blog/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{ asset('/themes/tlcommerce/public/css/custom_app.css') }}"> -->

    <style>:root { --mainC: {{ $theme_primary_color }}; }</style>

    <link rel="stylesheet" type="text/css" href="{{ asset(tenantCssRelativePath('back_to_top.css')) }}">
    <link rel="stylesheet" type="text/css" href="{{ asset(tenantCssRelativePath('header.css')) }}">
    <link rel="stylesheet" type="text/css" href="{{ asset(tenantCssRelativePath('header_logo.css')) }}">
    <link rel="stylesheet" type="text/css" href="{{ asset(tenantCssRelativePath('menu.css')) }}">
    <link rel="stylesheet" type="text/css" href="{{ asset(tenantCssRelativePath('blog.css')) }}">
    <link rel="stylesheet" type="text/css" href="{{ asset(tenantCssRelativePath('sidebar_options.css')) }}">
    <link rel="stylesheet" type="text/css" href="{{ asset(tenantCssRelativePath('page_404.css')) }}">
    <link rel="stylesheet" type="text/css" href="{{ asset(tenantCssRelativePath('subscribe.css')) }}">
    <link rel="stylesheet" type="text/css" href="{{ asset(tenantCssRelativePath('footer.css')) }}">
    <link rel="stylesheet" type="text/css" href="{{ asset(tenantCssRelativePath('social_icon.css')) }}">
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
    </style>
</head>

<body class="antialiased">
    <div id="app">
    </div>
    <link rel="stylesheet" type="text/css" href="{{ asset(tenantCssRelativePath('custom_css.css')) }}">
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

    <script>
        window.__TLC_BOOTSTRAP__ = {
            activeLayout: @json($activeLayoutBootstrap)
        };
    </script>

    <!-- <script src="{{ asset('themes/tlcommerce/public/js/main.js?v=210') }}"></script>  -->

    <!-- <script src="{{ asset('themes/tlcommerce/js/main.js?v=210') }}"></script> -->
     {{-- Bump ASSET_VERSION in webpack.mix.js whenever this ?v= changes --}}
     <script src="{{ asset('themes/tlcommerce/js/main.js?v=221') }}"></script>
    <!-- <script src="{{ asset('themes/tlcommerce/public/js/main.js?v=210') }}"></script> -->


    @endif


</body>

</html>
