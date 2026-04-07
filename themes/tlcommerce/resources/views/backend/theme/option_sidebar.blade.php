<a href="#" class="pl-3 py-2 parent-menu" id="general_menu" data-toggle="collapse" 
    data-target="collapseGeneral"
    aria-expanded="false" aria-controls="collapseGeneral" style="cursor: pointer;">
    <i class="icofont-ui-settings mr-2 black"></i> 
    <span class="black">{{ translate('General') }}</span>
</a>

<div id="collapseGeneral" class="collapse">
    <ul class="mb-0" style="list-style: none">
        <li class="py-1">
            <a class="black theme_option_link" id="back_to_top" href="#" data-menu="general_menu">
                <i class="icofont-ui-settings mr-2 black"></i> 
                <span>{{ translate('Back To Top') }}</span>
            </a>
        </li>
        <li class="py-1">
            <a class="black theme_option_link" id="theme_color" href="#" data-menu="general_menu">
                <i class="icofont-ui-settings mr-2 black"></i> 
                <span>{{ translate('Theme Color') }}</span>
            </a>
        </li>
        <li class="py-1">
            <a class="black theme_option_link" id="dark_light_switcher" href="#" data-menu="general_menu">
                <i class="icofont-ui-settings mr-2 black"></i> 
                <span>{{ translate('Dark/ Light Switcher') }}</span>
            </a>
        </li>
    </ul>
</div>

<a class="pl-3 py-2 black parent-menu" id="typography_menu" data-toggle="collapse" data-target="collapseTypography"
    aria-expanded="false" aria-controls="collapseTypography" style="cursor: pointer;">
    <i class="icofont-font mr-2 black"></i> 
    <span class="black">{{ translate('Typography') }}</span>
    <span class="caret mr-auto"></span>
</a>

<div id="collapseTypography" class="pl-2 collapse">
    <ul class="mb-0" style="list-style: none">
        <li class="py-1">
            <a class="black theme_option_link" id="body_typography" href="#" data-menu="typography_menu">
                <i class="icofont-font mr-2 black"></i> 
                <span>{{ translate('Body Typography') }}</span>
            </a>
        </li>
        <li class="py-1">
            <a class="black theme_option_link" id="paragraph_typography" href="#"data-menu="typography_menu">
                <i class="icofont-font mr-2 black"></i> 
                <span>{{ translate('Paragraph Typography') }}</span>
            </a>
        </li>
        <li class="py-1">
            <a class="black theme_option_link" id="heading_typography" href="#" data-menu="typography_menu">
                <i class="icofont-font mr-2 black"></i> 
                <span>{{ translate('Heading Typography') }}</span>
            </a>
        </li>
        <li class="py-1">
            <a class="black theme_option_link" id="menu_typography" href="#" data-menu="typography_menu">
                <i class="icofont-font mr-2 black"></i> 
                <span>{{ translate('Menu Typography') }}</span>
            </a>
        </li>
        <li class="py-1">
            <a class="black theme_option_link" id="button_typography" href="#" data-menu="typography_menu">
                <i class="icofont-font mr-2 black"></i> 
                <span>{{ translate('Button Typography') }}</span>
            </a>
        </li>
        <li class="py-1">
            <a class="black theme_option_link" id="custom_fonts" href="#" data-menu="typography_menu">
                <span>{{ translate('Custom Fonts') }}</span>
            </a>
        </li>
    </ul>
</div>

<a class="pl-3 py-2 black parent-menu" id="header_menu" data-toggle="collapse" data-target="collapseHeader" aria-expanded="false"
    aria-controls="collapseHeader" style="cursor: pointer;">
    <i class="icofont-credit-card mr-2 black"></i> <span class="black">{{ translate('Header') }}</span>
    <span class="caret mr-auto"></span>
</a>

<div id="collapseHeader" class="pl-2 collapse">
    <ul class="mb-0" style="list-style: none">
        <li class="py-1">
            <a class="black theme_option_link" id="header" href="#" data-menu="header_menu">
                <i class="icofont-credit-card mr-2 black"></i> 
                <span>{{ translate('Header Option') }}</span>
            </a>
        </li>
        <li class="py-1">
            <a class="black theme_option_link" id="header_logo" href="#" data-menu="header_menu">
                <i class="icofont-credit-card mr-2 black"></i> 
                <span>{{ translate('Header Logo') }}</span>
            </a>
        </li>
        <li class="py-1">
            <a class="black theme_option_link" id="menu" href="#" data-menu="header_menu">
                <i class="icofont-credit-card mr-2 black"></i> 
                <span>{{ translate('Menu') }}</span>
            </a>
        </li>
    </ul>
</div>
<!--Topbar banner-->
<a class="pl-3 py-2 black theme_option_link" id="topbar_banner" href="#"><i class="icofont-barricade"></i>
    <span>{{ translate('Topbar Banner') }}</span>
</a>
<!--End topbar banner-->
<!--Website popup-->
<a class="pl-3 py-2 black theme_option_link" id="website_popup" href="#"><i class="icofont-billboard"></i>
    <span>{{ translate('Website Popup') }}</span>
</a>
<!--End Website popup-->

<a class="pl-3 py-2 black" id="blog_menu" data-toggle="collapse" data-target="collapseBlog" aria-expanded="false"
    aria-controls="collapseBlog" style="cursor: pointer;">
    <i class="icofont-blogger mr-2 black"></i> <span class="black">{{ translate('Blog') }}</span>
    <span class="caret mr-auto"></span>
</a>

<div id="collapseBlog" class="pl-2 collapse">
    <ul class="mb-0" style="list-style: none">
        <li class="py-1">
            <a class="black theme_option_link" id="blog" href="#" data-menu="blog_menu">
                <span>{{ translate('Blog Option') }}</span>
            </a>
        </li>
        <li class="py-1">
            <a class="black theme_option_link" id="single_blog_page" href="#" data-menu="blog_menu">
                <span>{{ translate('Single Blog Page') }}</span>
            </a>
        </li>
        <li class="py-1">
            <a class="black theme_option_link" id="sidebar_options" href="#" data-menu="blog_menu">
                <span>{{ translate('Sidebar Options') }}</span>
            </a>
        </li>
    </ul>
</div>

<a class="pl-3 py-2 black theme_option_link" id="page_404" href="#"><i class="icofont-ban"></i>
    <span>{{ translate('404 Page') }}</span></a>

<a class="pl-3 py-2 black theme_option_link" id="subscribe" href="#"><i class="icofont-eject"></i>
    <span>{{ translate('Subscribe') }}</span></a>

<a class="pl-3 py-2 black theme_option_link" id="social" href="#"><i class="icofont-users-social"></i>
    <span>{{ translate('Social') }}</span></a>

<a class="pl-3 py-2 black theme_option_link" id="footer" href="#"><i class="icofont-notepad"></i>
    <span>{{ translate('Footer') }}</span></a>

<a class="pl-3 py-2 black theme_option_link" id="custom_css" href="#"><i class="icofont-file-css"></i>
    <span>{{ translate('Custom Css') }}</span>
</a>

<a class="pl-3 py-2 black theme_option_link" id="custom_js" href="#"><i class="icofont-file-javascript"></i>
    <span>{{ translate('Custom Script') }}</span>
</a>

<!--GDPR-->
<a class="pl-3 py-2 black theme_option_link" id="gdpr" href="#"><i class="icofont-certificate"></i>
    <span>{{ translate('GDPR (Cookies Consent)') }}</span>
</a>
<!--End GDPR-->


<style>

        .theme_option_link.active {
            background-color: #ff5a1f !important;
            color: #ffffff !important;
            border-radius: 12px; 
            width: 90%;
            margin-left: 10px;
            padding: 10px;
            font-size: 12px !important;
        }

        .theme_option_link.active:hover,
        .parent-menu.active:hover {
            color: #ffffff !important;
            opacity: 0.9;
        }

        .theme_option_link:hover, 
         .parent-menu:hover {
            color: #ff5a1f !important;
            opacity: 0.9;
        }

        .parent-menu:hover span.black {
            color: #ff5a1f !important;
        }

        .theme_option_link.active i {
            color: #ffffff !important;
        }

        .theme_option_link.active:hover i {
            color: #ffffff !important;
        }

        .theme_option_link:hover i,
        .parent-menu:hover i {
            color: #ff5a1f !important; 
            transition: color 0.3s ease;
        }

        .theme_option_link i {
            color: #666; 
            vertical-align: middle;
            margin-right: 8px; 
        }

    @media (max-width: 900px) {

        .col-md-3 {
            max-width: 80px;
            flex: 0 0 80px;
            overflow: visible !important; /* allow submenu to escape the column */
        }

        /* Position context must be on the parent-menu anchor, NOT the sidebar or column */
        .theme_option_sidebar .parent-menu {
            position: relative;
        }

        /* Remove position:relative from sidebar so it doesn't trap the absolute child */
        .theme_option_sidebar {
            position: static;
        }

        .theme_option_sidebar .collapse {
            position: absolute;
            left: 0;           /* aligns to the left edge of the parent-menu anchor */
            top: 100%;         /* drops directly below the icon */
            width: 200px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.15);
            padding: 10px;
            z-index: 9999;
        }

        /* Reset UL spacing */
        .theme_option_sidebar .collapse ul {
            padding-left: 0;
            margin: 0;
        }

        /* Restore text inside submenu */
        .theme_option_sidebar .collapse span {
            display: inline !important;
        }

        /* Proper link layout inside submenu */
        .theme_option_sidebar .collapse a {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            padding: 8px 10px;
            white-space: nowrap;
        }

    }
</style>


