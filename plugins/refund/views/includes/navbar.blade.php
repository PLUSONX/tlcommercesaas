<!--Refunds Module-->
@if (auth()->user()->can('Manage Refund Requests') || auth()->user()->can('Manage Refund reasons'))
    <li style="padding-left: 0 !important;"
        class="hide-menu {{ Request::routeIs(['plugin.refund.requests', 'plugin.refund.reason.edit', 'plugin.refund.reasons.list']) ? 'active sub-menu-opened' : '' }}">
        <!-- <li style="padding-left: 0 !important;"> -->
        <a href="#">
            <!-- <i class="icofont-ui-previous"></i> -->
            <x-lucide-banknote-arrow-down style="width: 20px; height: 20px; margin-left: 8px;" />
            <span class="link-title ml-2">{{ translate('Refunds') }}</span>
        </a>
        <ul class="nav sub-menu">
            @if (auth()->user()->can('Manage Refund Requests'))
                <li class="{{ Request::routeIs(['plugin.refund.requests']) ? 'active ' : '' }}">
                    <a class="pl-2" href="{{ route('plugin.refund.requests') }}">{{ translate('Refund Requests') }}</a>
                </li>
            @endif
            @if (auth()->user()->can('Manage Refund reasons'))
                <li
                    class="{{ Request::routeIs(['plugin.refund.reason.edit', 'plugin.refund.reasons.list']) ? 'active ' : '' }}">
                    <a class="pl-2" href="{{ route('plugin.refund.reasons.list') }}">{{ translate('Refund Reasons') }}</a>
                </li>
            @endif

        </ul>
    </li>
@endif
<!--End Refunds Module-->

<style>

    .sidebar .nav li.active.sub-menu-opened > a {
        background-color: transparent !important;
        color: #000000 !important; /* Change to your preferred default text color */
    }

    /* 2. Ensure the icon (SVG) inside the opened parent also resets to black/default */
    .sidebar .nav li.active.sub-menu-opened > a svg {
        stroke: #000000 !important; /* Adjust color as needed */
        color: #000000 !important;
    }

    /* 3. Keep the background ONLY for the actual active sub-menu item */
    .sidebar .nav li.sub-menu-opened .sub-menu li.active > a {
        background-color: #ff5A1f !important;
        color: #ffffff !important;
        border-radius: 12px;
    }

    @media (max-width: 900px) {
         .hide-menu {
            display: none !important;
        }
    }

</style>
