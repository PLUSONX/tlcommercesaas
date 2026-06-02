

@php
    $user = auth()->user();
    $roles = $user ? $user->roles : collect();
    $permissions = $user ? $user->permissions : collect();
    $allPermissions = $user ? $user->getAllPermissions() : collect();
@endphp

<nav class="sidebar" data-trigger="scrollbar">
    <!--Search Options-->
    <div class="sidebar-search-bar m-2">
        <div class="input-group addon ov-hidden">
            <input type="text" class="theme-input-style search-in-sidebar"
                placeholder="{{ translate('Search in Menu') }}">
        </div>
    </div>
    <!--End search options-->
    <!-- Sidebar Header -->
    <div class="sidebar-header d-none d-lg-block pt-0">
        <div class="sidebar-toogle-pin" style="margin-left: 8px;">
            <!-- <i class="icofont-tack-pin"></i> -->
             <x-lucide-pin class="icofont-tack-pin link-title" style="width: 20px; height: 20px;" />
        </div>
    </div>
    <!-- End Sidebar Header -->
    <!-- Sidebar Body -->
    <div class="sidebar-body main-side-bar">
        <!-- Nav -->
        <ul class="nav" id="main-nav">
            @if (auth()->user()->can('Manage Dashboard'))
                <li class="{{ Request::routeIs('admin.dashboard') ? 'active ' : '' }}" style="padding-left: 0 !important;">
                    <a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center">
                        <x-lucide-house style="width: 20px; height: 20px; margin-left: 8px;" />
                        <span class="link-title ml-2">{{ translate('Dashboard') }}</span>
                    </a>
                </li>
            @endif

             <!--Plugin nabvar options-->
            @foreach (pluginsNavbar() as $item)
                @includeIf($item)
            @endforeach
            <!--End Plugin nabvar options-->

            @if (auth()->user()->can('Manage Media'))
                <li class="users-menu {{ Request::routeIs(['core.media.page']) ? 'active ' : '' }}" style="padding-left: 0 !important;">
                    <a href="{{ route('core.media.page') }}" class="d-flex align-items-center">
                        <x-lucide-library style="width: 20px; height: 20px; margin-left: 8px;" />
                        <span class="link-title ml-2">{{ translate('File Manager') }}</span>
                    </a>

                </li>
            @endif
            <!-- Blog & Page-->
            <!--Blog Module-->
            <!-- @canany(['Show Blog', 'Create Blog', 'Manage Category', 'Manage Tag', 'Manage Comment'])
                <li
                    class="{{ Request::routeIs(['core.blog.category', 'core.add.blog.category', 'core.edit.blog.category', 'core.blog', 'core.add.blog', 'core.edit.blog', 'core.tag', 'core.edit.tag', 'core.add.tag', 'core.blog.comment', 'core.blog.comment.edit', 'core.blog.comment.setting']) ? 'active sub-menu-opened' : '' }}">
                    <a href="#">
                        <i class="icofont-blogger"></i>
                        <span class="link-title">{{ translate('Blog') }}</span>
                    </a>
                    <ul class="nav sub-menu">
                        @can('Show Blog')
                            <li class="{{ Request::routeIs(['core.blog', 'core.edit.blog']) ? 'active ' : '' }}">
                                <a href="{{ route('core.blog') }}">{{ translate('All Blogs') }}</a>
                            </li>
                        @endcan
                        @can('Create Blog')
                            <li class="{{ Request::routeIs('core.add.blog') ? 'active ' : '' }}">
                                <a href="{{ route('core.add.blog') }}">{{ translate('Add New Blog') }}</a>
                            </li>
                        @endcan
                        @can('Manage Category')
                            <li
                                class="{{ Request::routeIs(['core.blog.category', 'core.add.blog.category', 'core.edit.blog.category']) ? 'active ' : '' }}">
                                <a href="{{ route('core.blog.category') }}">{{ translate('Categories') }}</a>
                            </li>
                        @endcan
                        @can('Manage Tag')
                            <li class="{{ Request::routeIs(['core.tag', 'core.add.tag', 'core.edit.tag']) ? 'active ' : '' }}">
                                <a href="{{ route('core.tag') }}">{{ translate('Tags') }}</a>
                            </li>
                        @endcan
                        @can('Manage Comment')
                            <li
                                class="{{ Request::routeIs(['core.blog.comment', 'core.blog.comment.edit']) ? 'active ' : '' }}">
                                <a href="{{ route('core.blog.comment') }}">{{ translate('Comments') }}</a>
                            </li>
                            <li class="{{ Request::routeIs(['core.blog.comment.setting']) ? 'active sub-menu-opened' : '' }}">
                                <a href="#">
                                    <span class="link-title">{{ translate('Settings') }}</span>
                                </a>
                                <ul class="nav sub-menu">
                                    @if (!isTenant())
                                        <li class="{{ Request::routeIs(['core.blog.share.options']) ? 'active' : '' }}">
                                            <a
                                                href="{{ route('core.blog.share.options') }}">{{ translate('Blog Share Settings') }}</a>
                                        </li>
                                        <li class="{{ Request::routeIs(['core.blog.ai.setting']) ? 'active' : '' }}">
                                            <a
                                                href="{{ route('core.blog.ai.setting') }}">{{ translate('Open AI Settings') }}</a>
                                        </li>
                                    @endif
                                    <li class="{{ Request::routeIs(['core.blog.comment.setting']) ? 'active' : '' }}">
                                        <a
                                            href="{{ route('core.blog.comment.setting') }}">{{ translate('Comment Settings') }}</a>
                                    </li>
                                </ul>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endcanany -->
            <!--End Blog module-->

            <!--Page Module-->
            <!-- @canany(['Show Page', 'Create Page'])
                <li
                    class="{{ Request::routeIs(['core.page', 'core.page.add', 'core.page.edit']) ? 'active sub-menu-opened' : '' }}">
                    <a href="#">
                        <i class="icofont-page"></i>
                        <span class="link-title">{{ translate('Pages') }}</span>
                    </a>
                    <ul class="nav sub-menu">
                        @can('Show Page')
                            <li class="{{ Request::routeIs(['core.page', 'core.page.edit']) ? 'active ' : '' }}">
                                <a href="{{ route('core.page') }}">{{ translate('All Pages') }}</a>
                            </li>
                        @endcan
                        @can('Create Page')
                            <li class="{{ Request::routeIs('core.page.add') ? 'active ' : '' }}">
                                <a href="{{ route('core.page.add') }}">{{ translate('Add New Page') }}</a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endcanany -->
            <!--End Blog module-->
            <!-- Blog & Page -->

            <!--Appearances Modules-->
            <!-- @if (auth()->user()->can('Manage Themes') || auth()->user()->can('Manage Menus'))
                <li
                    class="{{ Request::routeIs(['core.themes.index', 'core.manage.menus']) ? 'active sub-menu-opened' : '' }}">
                    <a href="#">
                        <i class="icofont-brand-designfloat"></i>
                        <span class="link-title">{{ translate('Appearances') }}</span>
                    </a>
                    <ul class="nav sub-menu">
                        @if (auth()->user()->can('Manage Themes'))
                            <li class="{{ Request::routeIs(['core.themes.index']) ? 'active ' : '' }}">
                                <a href="{{ route('core.themes.index') }}">{{ translate('Themes') }}</a>
                            </li>
                        @endif 
                        @if (auth()->user()->can('Manage Menus'))
                            <li class="{{ Request::routeIs(['core.manage.menus']) ? 'active ' : '' }}">
                                <a href="{{ route('core.manage.menus') }}">{{ translate('Menus') }}</a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif -->
            <!--End Appearances Modules-->

            <!--Theme otions-->
            @includeIf(getActiveThemeOptions())
            <!--End Theme options-->

            <!-- @if (!isTenant() && auth()->user()->can('Manage Plugins'))
                <li class="{{ Request::routeIs(['core.plugins.index', 'core.plugins.create']) ? 'active' : '' }}">
                    <a href="{{ route('core.plugins.index') }}">
                        <i class="icofont-addons"></i>
                        <span class="link-title">{{ translate('Plugins') }}</span>
                    </a>
                </li>
            @endif -->

            @if (auth()->user()->can('Manage General Settings') ||
                    auth()->user()->can('Manage Email Settings') ||
                    auth()->user()->can('Manage Email Templates') ||
                    auth()->user()->can('Manage Language') ||
                    auth()->user()->can('Manage Media Settings') ||
                    auth()->user()->can('Manage Seo Settings'))
                <!--Settings Modules-->
                <li style="padding-left: 0 !important;"
                    class="{{ Request::routeIs(['core.seo.settings', 'core.email.smtp.configuration', 'core.language.frontend.translations', 'core.image.settings', 'core.email.templates', 'core.language.edit', 'core.language.key.values', 'core.languages', 'core.language.new', 'core.image.settings', 'core.social.media.login.settings', 'core.general.settings']) ? 'active sub-menu-opened' : '' }}">
                    <li style="padding-left: 0 !important;">
                    <a href="#">
                        <!-- <i class="icofont-settings-alt"></i> -->
                        <x-lucide-settings style="width: 20px; height: 20px; margin-left: 8px;" />
                        <span class="link-title ml-2">{{ translate('Settings') }}</span>
                    </a>
                    <ul class="nav sub-menu">
                        @if (auth()->user()->can('Manage General Settings'))
                            <li class="{{ Request::routeIs(['core.general.settings']) ? 'active ' : '' }}">
                                <a class="pl-2" href="{{ route('core.general.settings') }}">{{ translate('General settings') }}</a>
                            </li>
                        @endif

                        @if (auth()->user()->can('Manage Email Settings'))
                            <li class="{{ Request::routeIs(['core.email.smtp.configuration']) ? 'active ' : '' }}">
                                <a class="pl-2"
                                    href="{{ route('core.email.smtp.configuration') }}">{{ translate('Email settings') }}</a>
                            </li>
                        @endif

                        @if (auth()->user()->can('Manage Email Templates'))
                            <li class="{{ Request::routeIs(['core.email.templates']) ? 'active ' : '' }}">
                                <a class="pl-2" href="{{ route('core.email.templates') }}">{{ translate('Email Templates') }}</a>
                            </li>
                        @endif

                        @if (auth()->user()->can('Manage Language'))
                            <li
                                class="{{ Request::routeIs(['core.language.edit', 'core.language.key.values', 'core.languages', 'core.language.new']) ? 'active ' : '' }}">
                                <a class="pl-2" href="{{ route('core.languages') }}">{{ translate('Languages') }}</a>
                            </li>
                        @endif

                        @if (auth()->user()->can('Manage Media Settings'))
                            <li class="{{ Request::routeIs(['core.image.settings']) ? 'active ' : '' }}">
                                <a class="pl-2" href="{{ route('core.image.settings') }}">{{ translate('Media settings') }}</a>
                            </li>
                        @endif

                        @if (auth()->user()->can('Manage Seo Settings'))
                            <li class="{{ Request::routeIs(['core.seo.settings']) ? 'active ' : '' }}">
                                <a class="pl-2" href="{{ route('core.seo.settings') }}">{{ translate('SEO settings') }}</a>
                            </li>
                        @endif
                    </ul>
                </li>
                <!--End Settings Modules-->
            @endif

            <!--Users Module-->
            @if (auth()->user()->can('Show User') || auth()->user()->can('Show Role') || auth()->user()->can('Show Permission'))
                <li style="padding-left: 0 !important;"
                    class="users-menu {{ Request::routeIs(['core.roles', 'core.permissions', 'core.users', 'core.add.user', 'core.edit.user']) ? 'active sub-menu-opened' : '' }}">
                    <!-- <li style="padding-left: 0 !important;"> -->
                    <a href="#">
                        <!-- <i class="icofont-users-social"></i> -->
                        <x-lucide-user style="width: 20px; height: 20px; margin-left: 8px;" />
                        <span class="link-title ml-2">{{ translate('Users') }}</span>
                    </a>
                    <ul class="nav sub-menu">
                        @if (auth()->user()->can('Show User'))
                            <li
                                class="{{ Request::routeIs(['core.users', 'core.add.user', 'core.edit.user']) ? 'active ' : '' }}">
                                <a class="pl-2" href="{{ route('core.users') }}">{{ translate('Users') }}</a>
                            </li>
                        @endif

                        @if (auth()->user()->can('Show Role'))
                            <li class="{{ Request::routeIs(['core.roles']) ? 'active ' : '' }}">
                                <a class="pl-2"
                                    href="{{ route('core.roles') }}">{{ translate('Roles') }}</a></li>
                        @endif

                        @if (auth()->user()->can('Show Permission'))
                            <li class="{{ Request::routeIs(['core.permissions']) ? 'active ' : '' }}">
                                <a class="pl-2" href="{{ route('core.permissions') }}">{{ translate('Permissions') }}</a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif
            <!--End users-->

            <!--Activity Logs Module-->
            @if (auth()->user()->can('Manage Login activity'))
                <li style="padding-left: 0 !important;"
                    class="users-menu {{ Request::routeIs(['core.activity.logs', 'core.get.login.activity']) ? 'active sub-menu-opened' : '' }}">
                    <!-- <li style="padding-left: 0 !important;"> -->
                    <a href="#">
                        <!-- <i class="icofont-ui-password"></i> -->
                        <x-lucide-clipboard-clock style="width: 20px; height: 20px; margin-left: 8px;" />
                        <span class="link-title ml-2">{{ translate('Activity Logs') }}</span>
                    </a>
                    <ul class="nav sub-menu">
                        <li class="{{ Request::routeIs(['core.get.login.activity']) ? 'active ' : '' }}">
                            <a class="pl-2" href="{{ route('core.get.login.activity') }}">{{ translate('Login activity') }}</a>
                        </li>
                    </ul>
                </li>
            @endif
            <!--Activity Logs Settings Module-->
            @if (!isTenant() && auth()->user()->hasRole('Super Admin'))
                <!--System Module--->
                <li
                    class="{{ Request::routeIs(['core.system.update.page', 'core.backup.files.list', 'core.backup.database.list']) ? 'active sub-menu-opened' : '' }}">
                    <a href="#">
                        <i class="icofont-wrench"></i>
                        <span class="link-title">{{ translate('System') }}</span>
                    </a>
                    <ul class="nav sub-menu">
                        <li class="{{ Request::routeIs(['core.admin.site.map']) ? 'active ' : '' }}">
                            <a href="{{ route('core.admin.site.map') }}">{{ translate('Sitemap') }}</a>
                        </li>
                        <li class="{{ Request::routeIs(['core.system.update.page']) ? 'active ' : '' }}">
                            <a href="{{ route('core.system.update.page') }}">{{ translate('Update') }}</a>
                        </li>
                        <li class="{{ Request::routeIs(['core.backup.files.list']) ? 'active ' : '' }}">
                            <a href="{{ route('core.backup.files.list') }}">{{ translate('Backups') }}</a>
                        </li>
                    </ul>
                </li>
                <!--End system Module-->
            @endif
            @if (isTenant() && auth()->user()->hasRole('Super Admin'))
                <!--System Module--->
                <li
                    class="{{ Request::routeIs(['core.system.update.page', 'core.backup.files.list', 'core.backup.database.list']) ? 'active sub-menu-opened' : '' }}">
                    <a href="#">
                        <i class="icofont-wrench"></i>
                        <span class="link-title">{{ translate('System') }}</span>
                    </a>
                    <ul class="nav sub-menu">
                        <li class="{{ Request::routeIs(['core.admin.site.map']) ? 'active ' : '' }}">
                            <a href="{{ route('core.admin.site.map') }}">{{ translate('Sitemap') }}</a>
                        </li>
                    </ul>
                </li>
                <!--End system Module-->
            @endif
        </ul>
        <!-- End Nav -->
    </div>
    <!-- End Sidebar Body -->
    <!--Side bar search result-->
    <div class="sidebar-body search-side-bar d-none">
        <!-- Nav -->
        <ul class="nav">
            <li>Hello</li>
        </ul>
    </div>
    <!--End sidebar search result-->
</nav>


<style>

    @media (max-width: 900px) {
         .users-menu {
            display: none !important;
        }
    }

    /* .sidebar .nav li.active > a,
    .sidebar .nav li.active > a:hover,
    .sidebar .nav li.active > a:focus {
        background-color: #ff5A1f !important;
        color: #ffffff !important;
        border-radius: 12px
    }

    .sidebar .nav li.active > a svg {
        stroke: #ffffff !important;
        color: #ffffff !important;
    }
    */
    
    .icofont-tack-pin {
        color: #000 !important;
    }

    .icofont-tack-pin:hover {
        color: #ff5A1f !important;
    }


     /* ============================================
       PARENT MENU ITEMS (with children)
       ============================================ */

    /* Parent WITH sub-menu: always transparent, no background in any state */
    .sidebar .nav li:has(> .sub-menu) > a,
    .sidebar .nav li:has(> .sub-menu) > a:hover,
    .sidebar .nav li:has(> .sub-menu).active > a,
    .sidebar .nav li:has(> .sub-menu).active > a:hover {
        background-color: transparent !important;
        border-radius: 0 !important;
        box-shadow: none !important;
    }

    /* Parent WITH sub-menu hover: only font + icon turn orange */
    .sidebar .nav li:has(> .sub-menu) > a:hover,
    .sidebar .nav li:has(> .sub-menu).active > a:hover {
        color: #ff5a1f !important;
    }

    /* Sync SVG icon color on hover */
    .sidebar .nav li:has(> .sub-menu) > a:hover svg,
    .sidebar .nav li:has(> .sub-menu).active > a:hover svg {
        stroke: #ff5a1f !important;
    }

    /* ============================================
       PARENT MENU ITEMS (without children / standalone)
       ============================================ */

    /* Standalone active parent: orange background */
    .sidebar .nav > li.active:not(:has(> .sub-menu)) > a {
        background-color: #ff5a1f !important;
        color: #ffffff !important;
        border-radius: 12px;
    }

    /* Standalone active parent icon: white */
    .sidebar .nav > li.active:not(:has(> .sub-menu)) > a svg {
        stroke: #ffffff !important;
    }

    /* Standalone parent hover: orange background */
    .sidebar .nav > li:not(:has(> .sub-menu)) > a:hover {
        background-color: #ff5a1f !important;
        color: #ffffff !important;
        border-radius: 12px;
    }

    /* Standalone parent hover icon: white */
    .sidebar .nav > li:not(:has(> .sub-menu)) > a:hover svg {
        stroke: #ffffff !important;
    }

    /* Parent WITH sub-menu that is currently OPEN: keep font orange */
    .sidebar .nav li.sub-menu-opened:has(> .sub-menu) > a {
        color: #ff5a1f !important;
    }

    .sidebar .nav li.sub-menu-opened:has(> .sub-menu) > a svg {
        stroke: #ff5a1f !important;
    }


    /* ============================================
       CHILD MENU ITEMS (depth 1)
       ============================================ */

    /* Active child: orange background (leaf items only, not nested parents) */
    .sidebar .nav .sub-menu > li.active:not(:has(> .sub-menu)) > a {
        background-color: #ff5a1f !important;
        color: #ffffff !important;
        border-radius: 12px;
    }

    /* Active child icon: white */
    .sidebar .nav .sub-menu > li.active:not(:has(> .sub-menu)) > a svg {
        stroke: #ffffff !important;
    }

    /* Child hover: only font color, no background */
    .sidebar .nav .sub-menu > li:not(:has(> .sub-menu)) > a:hover {
        background-color: transparent !important;
        color: #ff5a1f !important;
        text-decoration: none;
    }

    /* Active child being hovered: keep orange background */
    .sidebar .nav .sub-menu > li.active:not(:has(> .sub-menu)) > a:hover {
        background-color: #ff5a1f !important;
        color: #ffffff !important;
    }

    /* ============================================
       LOCATIONS SUB-SUB-MENU (depth 2)
       ============================================ */

    /* Locations parent (has sub-menu): transparent always */
    .sidebar .nav .sub-menu > li:has(> .sub-menu) > a,
    .sidebar .nav .sub-menu > li:has(> .sub-menu) > a:hover,
    .sidebar .nav .sub-menu > li:has(> .sub-menu).active > a,
    .sidebar .nav .sub-menu > li:has(> .sub-menu).active > a:hover {
        background-color: transparent !important;
        border-radius: 0 !important;
        box-shadow: none !important;
    }

    /* Locations parent hover: orange font only */
    .sidebar .nav .sub-menu > li:has(> .sub-menu) > a:hover {
        color: #ff5a1f !important;
    }

    /* Nested parent open state: orange label when child route active */
    .sidebar .sidebar-body .nav .sub-menu > li.sub-menu-opened:has(> .sub-menu) > a {
        color: #ff5a1f !important;
    }

    /* Suppress global theme li:before bar on nested parents */
    .sidebar .sidebar-body .nav .sub-menu > li:has(> .sub-menu).active:before,
    .sidebar .sidebar-body .nav .sub-menu > li:has(> .sub-menu).sub-menu-opened:before {
        background-color: transparent !important;
    }

    /* Locations grandchild active: orange background */
    .sidebar .nav .sub-menu .sub-menu > li.active > a {
        background-color: #ff5a1f !important;
        color: #ffffff !important;
        border-radius: 12px;
    }

    /* Locations grandchild hover: orange font only */
    .sidebar .sidebar-body .nav .sub-menu .sub-menu > li > a:hover {
        background-color: transparent !important;
        color: #ff5a1f !important;
        text-decoration: none;
    }

    /* Locations grandchild active hovered: keep orange */
    .sidebar .nav .sub-menu .sub-menu > li.active > a:hover {
        background-color: #ff5a1f !important;
        color: #ffffff !important;
    }
</style>
