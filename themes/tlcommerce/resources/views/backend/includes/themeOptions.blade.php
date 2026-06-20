<!--Theme Options Modules-->
@if (auth()->user()->can('Manage Theme General settings') ||
        auth()->user()->can('Manage Home Page Builder') ||
        auth()->user()->can('Manage Slider Settings') ||
        auth()->user()->can('Manage Widget') ||
        auth()->user()->can('Manage Layout Settings') ||
        auth()->user()->can('Manage Quiz'))
    <li style="padding-left: 0 !important;"
        class="hide-menu {{ Request::routeIs(['theme.tlcommerce.home.page.sections.edit', 'theme.tlcommerce.home.page.sections.new', 'theme.tlcommerce.home.page.sections', 'theme.tlcommerce.sliders.edit', 'theme.tlcommerce.sliders.new', 'theme.tlcommerce.sliders', 'theme.tlcommerce.options', 'theme.tlcommerce.quiz.list', 'theme.tlcommerce.quiz.new', 'theme.tlcommerce.quiz.edit', 'theme.tlcommerce.quiz.questions', 'theme.tlcommerce.quiz.answers', 'theme.tlcommerce.quiz.scores']) ? 'active sub-menu-opened' : '' }}">
        <!-- <li style="padding-left: 0 !important;"> -->
        <a href="#">
            <!-- <i class="icofont-ui-theme"></i> -->
            <x-lucide-badge-percent style="width: 20px; height: 20px; margin-left: 8px;" />
            <span class="link-title ml-2">{{ translate('Theme Options') }}</span>
        </a>
        <ul class="nav sub-menu">
            @if (auth()->user()->can('Manage Theme General settings'))
                <li class="{{ Request::routeIs(['theme.tlcommerce.options']) ? 'active ' : '' }}">
                    <a class="pl-2" href="{{ route('theme.tlcommerce.options') }}">{{ translate('General settings') }}</a>
                </li>
            @endif
            @if (auth()->user()->can('Manage Home Page Builder'))
                <li
                    class="{{ Request::routeIs(['theme.tlcommerce.home.page.sections.edit', 'theme.tlcommerce.home.page.sections']) ? 'active ' : '' }}">
                    <a class="pl-2" href="{{ route('theme.tlcommerce.home.page.sections') }}">{{ translate('Home Page Builder') }}</a>
                </li>
            @endif
            @if (auth()->user()->can('Manage Slider Settings'))
                <li class="{{ Request::routeIs(['theme.tlcommerce.sliders']) ? 'active ' : '' }}">
                    <a class="pl-2" href="{{ route('theme.tlcommerce.sliders') }}">{{ translate('Slider Settings') }}</a>
                </li>
            @endif
            @if (auth()->user()->can('Manage Widget'))
                <!--Widget Module-->
                <li class="{{ Request::routeIs(['theme.tlcommerce.widgets']) ? 'active ' : '' }}">
                    <a class="pl-2" href="{{ route('theme.tlcommerce.widgets') }}">
                        <span class="link-title">{{ translate('Widgets') }}</span>
                    </a>
                </li>
                <!--End Widget Module-->
            @endif
            
            @if (auth()->user()->can('Manage Layout Settings'))
                 <!--Layout Module-->
                <li class="{{ Request::routeIs(['theme.tlcommerce.layoutSettings']) ? 'active ' : '' }}">
                    <a class="pl-2" href="{{ route('theme.tlcommerce.layoutSettings') }}">
                        <span class="link-title">{{ translate('Layout Settings') }}</span>
                    </a>
                </li>
                <!--End Layout Module-->
            @endif

            @if (auth()->user()->can('Manage Quiz'))
                <li class="{{ Request::routeIs(['theme.tlcommerce.quiz.list', 'theme.tlcommerce.quiz.new', 'theme.tlcommerce.quiz.edit', 'theme.tlcommerce.quiz.questions', 'theme.tlcommerce.quiz.answers', 'theme.tlcommerce.quiz.scores']) ? 'active ' : '' }}">
                    <a class="pl-2" href="{{ route('theme.tlcommerce.quiz.list') }}">{{ translate('Quiz Builder') }}</a>
                </li>
            @endif
        </ul>
    </li>
@endif
<!--End Theme Options Modules-->

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

<!-- <style>

    .sidebar .nav li.active > a,
    .sidebar .nav li.active > a:hover,
    .sidebar .nav li.active > a:focus {
        background-color: #ff5A1f !important;
        color: #ffffff !important;
        border-radius: 12px
    }

    .sidebar .nav li:not(.active) > a:hover {
        color: #ff5A1f !important;
    }

    .sidebar .nav li:not(.active):hover > a {
        color: #ff5A1f !important;
    }

    /* 2. Handle the SVG icon color for the parent during that same hover */
    .sidebar .nav li:not(.active):hover > a svg {
        stroke: #ff5A1f !important;
        color: #ff5A1f !important;
    }

    .sidebar .nav li.active > a svg {
        stroke: #ffffff !important;
        color: #ffffff !important;
    }

</style> -->
