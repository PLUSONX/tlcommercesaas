<!-- Logo -->
@if ($mood == 'dark')
    <div class="logo pl-20 bg-transparent align-items-center d-flex">
        @if (sizeof($logo_details) > 0 && isset($logo_details['admin_dark_logo']))
            <a href="/" class="default-logo"><img
                    src="{{ project_asset($logo_details['admin_dark_logo']) }}"></a>
        @else
            <h3 class="default-logo">{{ $logo_details['system_name'] }}</h3>
        @endif

        @if (sizeof($logo_details) > 0 && isset($logo_details['admin_dark_mobile_logo']))
            <a href="/" class="mobile-logo"><img
                    src="{{ project_asset($logo_details['admin_dark_mobile_logo']) }}"></a>
        @else   
            <h3 class="mobile-logo">{{ $logo_details['system_name'] }}</h3>
        @endif
    </div>
@else
    <!-- <div class="logo pl-20 bg-white align-items-center d-flex"> -->
    <div class="pl-20 align-items-center d-flex"">
        <a href="/admin/dashboard">
            <img src="{{ asset('/themes/default/Platepilot_Logo.png') }}" width="200">
        </a>
        <!-- @if (sizeof($logo_details) > 0 && isset($logo_details['admin_logo']))
        @else
            <h3 class="default-logo">{{ $logo_details['system_name'] }}</h3>
        @endif

        @if (sizeof($logo_details) > 0 && isset($logo_details['admin_mobile_logo']))
            <a href="/" class="mobile-logo"><img
                    src="{{ project_asset($logo_details['admin_mobile_logo']) }}"></a>
        @else
            <h3 class="mobile-logo">{{ $logo_details['system_name'] }}</h3>
        @endif -->
    </div>
@endif
<!-- End Logo -->
