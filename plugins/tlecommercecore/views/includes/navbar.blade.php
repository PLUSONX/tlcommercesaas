@php
    //pickup order from plugin
    $isactivatePickupPoint = isActivePluging('pickuppoint');
    $order_pickup_point_active_link_file_links = [];
    $order_pickup_point_active_link_file =
        base_path() . '/plugins/pickuppoint/views/includes/submenu/order_active_link.json';
    if (file_exists($order_pickup_point_active_link_file)) {
        $order_pickup_point_active_link_file_links = json_decode(
            file_get_contents($order_pickup_point_active_link_file),
            true,
        );
    }
    $isactivateMultivendor = isActivePluging('multivendor');
    //Seller Products from plugin
    $seller_products_active_link_file_links = [];
    $seller_products_active_link_file =
        base_path() . '/plugins/multivendor/views/includes/submenu/products_active_link.json';
    if (file_exists($seller_products_active_link_file)) {
        $seller_products_active_link_file_links = json_decode(
            file_get_contents($seller_products_active_link_file),
            true,
        );
    }
    //Seller order from plugin
    $order_seller_active_link_file_links = [];
    $order_seller_active_link_file =
        base_path() . '/plugins/multivendor/views/includes/submenu/order_active_link.json';
    if (file_exists($order_seller_active_link_file)) {
        $order_seller_active_link_file_links = json_decode(file_get_contents($order_seller_active_link_file), true);
    }
@endphp

<!--Orders Module-->
@if (auth()->user()->can('Manage Inhouse Orders') || auth()->user()->can('Manage Pickup Point Order'))
    <li class="{{ Request::routeIs($order_pickup_point_active_link_file_links, $order_seller_active_link_file_links, ['plugin.tlcommercecore.orders.details', 'plugin.tlcommercecore.orders.inhouse']) ? 'active sub-menu-opened' : '' }}"
        style="padding-left: 0 !important;">
        <a href="#">
            <x-lucide-shopping-cart style="width: 20px; height: 20px; margin-left: 8px;" />
            <span class="link-title ml-2">{{ translate('Orders') }}</span>
        </a>
        <ul class="nav sub-menu">
            @if (auth()->user()->can('Manage Inhouse Orders'))
                <li class="{{ Request::routeIs(['plugin.tlcommercecore.orders.inhouse']) ? 'active' : '' }}">
                    <a class="pl-2"
                        href="{{ route('plugin.tlcommercecore.orders.inhouse') }}">{{ translate('Inhouse Orders') }}</a>
                </li>
            @endif
        </ul>
    </li>
@endif
<!--End Orders Module-->

<!--Customer Module-->
@if (auth()->user()->can('Manage Customers'))
    <li class="hide-menu {{ Request::routeIs(['plugin.tlcommercecore.customers.list']) ? 'active' : '' }}"
        style="padding-left: 0 !important;">
        <a href="{{ route('plugin.tlcommercecore.customers.list') }}">
            <x-lucide-users style="width: 20px; height: 20px; margin-left: 8px;" />
            <span class="link-title ml-2">{{ translate('Customers') }}</span>
        </a>
    </li>
@endif
<!--End Customer Module-->

<!--Products Module-->
@if (auth()->user()->can('Manage Add New Product') ||
        auth()->user()->can('Manage Inhouse Products') ||
        auth()->user()->can('Manage Colors') ||
        auth()->user()->can('Manage Brands') ||
        auth()->user()->can('Manage Categories') ||
        auth()->user()->can('Manage Attributes') ||
        auth()->user()->can('Manage Units') ||
        auth()->user()->can('Manage Product Reviews') ||
        auth()->user()->can('Manage Product collections') ||
        auth()->user()->can('Manage Product Tags') ||
        auth()->user()->can('Manage Product Conditions'))
    <li class="hide-menu {{ Request::routeIs($seller_products_active_link_file_links,
        ['plugin.tlcommercecore.product.reviews.list','plugin.tlcommercecore.product.collection.products',
        'plugin.tlcommercecore.product.collection.edit','plugin.tlcommercecore.product.collection.add.new',
        'plugin.tlcommercecore.product.collection.list','plugin.tlcommercecore.product.edit','plugin.tlcommercecore.product.list',
        'plugin.tlcommercecore.product.add.new','plugin.tlcommercecore.product.units.edit',
        'plugin.tlcommercecore.product.attributes.values.edit','plugin.tlcommercecore.product.attributes.values',
        'plugin.tlcommercecore.product.attributes.edit','plugin.tlcommercecore.product.attributes.add',
        'plugin.tlcommercecore.product.attributes.list','plugin.tlcommercecore.product.tags.edit',
        'plugin.tlcommercecore.product.tags.add.new','plugin.tlcommercecore.product.tags.list',
        'plugin.tlcommercecore.product.conditions.edit','plugin.tlcommercecore.product.conditions.new',
        'plugin.tlcommercecore.product.conditions.list','plugin.tlcommercecore.product.units.new',
        'plugin.tlcommercecore.product.units.list','plugin.tlcommercecore.product.colors.edit',
        'plugin.tlcommercecore.product.colors.list','plugin.tlcommercecore.product.colors.new',
        'plugin.tlcommercecore.product.brand.edit','plugin.tlcommercecore.product.brand.new',
        'plugin.tlcommercecore.product.category.list','plugin.tlcommercecore.product.category.new',
        'plugin.tlcommercecore.product.category.edit','plugin.tlcommercecore.product.brand.list']) ? 'active sub-menu-opened' : '' }}"
        style="padding-left: 0 !important;">
        <a href="#">
            <x-lucide-shopping-basket style="width: 20px; height: 20px; margin-left: 8px;" />
            <span class="link-title ml-2">{{ translate('Products') }}</span>
        </a>
        <ul class="nav sub-menu">
            @if (auth()->user()->can('Manage Inhouse Products'))
                <li class="{{ Request::routeIs(['plugin.tlcommercecore.product.list']) ? 'active' : '' }}">
                    <a class="pl-2"
                        href="{{ route('plugin.tlcommercecore.product.list') }}">{{ translate('All Products') }}</a>
                </li>
                @if ($isactivateMultivendor)
                    @includeIf('plugin/multivendor::includes.submenu.products')
                @endif
            @endif
            @if (auth()->user()->can('Manage Categories'))
                <li class="{{ Request::routeIs(['plugin.tlcommercecore.product.category.list', 'plugin.tlcommercecore.product.category.new', 'plugin.tlcommercecore.product.category.edit']) ? 'active' : '' }}">
                    <a class="pl-2"
                        href="{{ route('plugin.tlcommercecore.product.category.list') }}">{{ translate('Categories') }}</a>
                </li>
            @endif
            @if (auth()->user()->can('Manage Product Reviews'))
                <li class="{{ Request::routeIs(['plugin.tlcommercecore.product.reviews.list']) ? 'active' : '' }}">
                    <a class="pl-2"
                        href="{{ route('plugin.tlcommercecore.product.reviews.list') }}">{{ translate('Product Reviews') }}</a>
                </li>
            @endif

            @if (auth()->user()->can('Manage Product collections'))
                 <li
                     class="{{ Request::routeIs(['plugin.tlcommercecore.product.collection.list']) ? 'active ' : '' }}">
                     <a class="pl-2"
                         href="{{ route('plugin.tlcommercecore.product.collection.list') }}">{{ translate('Product collections') }}</a>
                 </li>
             @endif

            @if (auth()->user()->can('Manage Product conditions'))
                <li class="{{ Request::routeIs(['plugin.tlcommercecore.product.conditions.edit', 'plugin.tlcommercecore.product.conditions.new', 'plugin.tlcommercecore.product.conditions.list']) ? 'active' : '' }}">
                    <a class="pl-2"
                        href="{{ route('plugin.tlcommercecore.product.conditions.list') }}">{{ translate('Product conditions') }}</a>
                </li>
            @endif
        </ul>
    </li>
@endif
<!--End Products Module-->

<!--Ecommerce Settings Module-->
@if (auth()->user()->can('Manage Taxes') ||
        auth()->user()->can('Manage Settings') ||
        auth()->user()->can('Manage Currencies') ||
        auth()->user()->can('Manage Product Share Options'))
    <li style="padding-left: 0 !important;"
        class="hide-menu {{ Request::routeIs(['plugin.tlcommercecore.ecommerce.edit.currency', 'plugin.tlcommercecore.ecommerce.add.currency', 'plugin.tlcommercecore.ecommerce.all.currencies', 'plugin.tlcommercecore.ecommerce.configuration', 'plugin.tlcommercecore.ecommerce.settings.taxes.manage.rates', 'plugin.tlcommercecore.products.share.options', 'plugin.tlcommercecore.ecommerce.settings.taxes.list']) ? 'active sub-menu-opened' : '' }}">
        <a href="#">
            <x-lucide-layout-template style="width: 20px; height: 20px; margin-left: 8px;" />
            <span class="link-title ml-2">{{ translate('Ecommerce Settings') }}</span>
        </a>
        <ul class="nav sub-menu">
            @if (auth()->user()->can('Manage Settings'))
                <li class="{{ Request::routeIs(['plugin.tlcommercecore.ecommerce.configuration']) ? 'active' : '' }}">
                    <a class="pl-2"
                        href="{{ route('plugin.tlcommercecore.ecommerce.configuration') }}">{{ translate('Settings') }}</a>
                </li>
            @endif
            @if (auth()->user()->can('Manage Currencies'))
                <li class="{{ Request::routeIs(['plugin.tlcommercecore.ecommerce.all.currencies']) ? 'active' : '' }}">
                    <a class="pl-2"
                        href="{{ route('plugin.tlcommercecore.ecommerce.all.currencies') }}">{{ translate('Currencies') }}</a>
                </li>
            @endif
            @if (auth()->user()->can('Manage Product Share Options'))
                <li class="{{ Request::routeIs(['plugin.tlcommercecore.products.share.options']) ? 'active' : '' }}">
                    <a class="pl-2"
                        href="{{ route('plugin.tlcommercecore.products.share.options') }}">{{ translate('Product Share Options') }}</a>
                </li>
            @endif
        </ul>
    </li>
@endif
<!--End Ecommerce Settings Module-->

<!--Marketings Module-->
@php
    $isactivateFlashdeal = isActivePluging('flashdeal');
    $marketing_active_link_file_links = [];
    $marketing_active_link_file = base_path() . '/plugins/flashdeal/views/includes/submenu/marketing_active_link.json';
    if (file_exists($marketing_active_link_file)) {
        $marketing_active_link_file_links = json_decode(file_get_contents($marketing_active_link_file), true);
    }
    $isactivateCoupon = isActivePluging('coupon');
    $marketing_coupon_active_link_file_links = [];
    $marketing_coupon_active_link_file =
        base_path() . '/plugins/coupon/views/includes/submenu/marketing_active_link.json';
    if (file_exists($marketing_coupon_active_link_file)) {
        $marketing_coupon_active_link_file_links = json_decode(
            file_get_contents($marketing_coupon_active_link_file),
            true,
        );
    }
@endphp
@if (auth()->user()->can('Manage Flash Deals') ||
        auth()->user()->can('Manage Coupons') ||
        auth()->user()->can('Manage Custom notification'))
    <li style="padding-left: 0 !important;"
        class="hide-menu {{ Request::routeIs($marketing_coupon_active_link_file_links, $marketing_active_link_file_links, ['plugin.tlcommercecore.marketing.custom.notification', 'plugin.tlcommercecore.marketing.custom.notification.create.new']) ? 'active sub-menu-opened' : '' }}">
        <a href="#">
            <x-lucide-megaphone style="width: 20px; height: 20px; margin-left: 8px;" />
            <span class="link-title ml-2">{{ translate('Marketing') }}</span>
        </a>
        <ul class="nav sub-menu">
            @if ($isactivateFlashdeal)
                @includeIf('plugin/flashdeal::includes.submenu.marketing')
            @endif
            @if ($isactivateCoupon)
                @includeIf('plugin/coupon::includes.submenu.marketing')
            @endif
            @if (auth()->user()->can('Manage Custom notification'))
                <li class="{{ Request::routeIs(['plugin.tlcommercecore.marketing.custom.notification', 'plugin.tlcommercecore.marketing.custom.notification.create.new']) ? 'active' : '' }}">
                    <a class="pl-2"
                        href="{{ route('plugin.tlcommercecore.marketing.custom.notification') }}">{{ translate('Custom Notification') }}</a>
                </li>
            @endif
            <li class="{{ Request::routeIs(['plugin.tlcommercecore.marketing.social.media.integration']) ? 'active' : '' }}">
                <a class="pl-2"
                    href="{{ route('plugin.tlcommercecore.marketing.social.media.integration') }}">{{ translate('Social Media Integration') }}</a>
            </li>
        </ul>
    </li>
@endif
<!--End Marketings Module-->

<!--Report Module-->
@if (auth()->user()->can('Manage Product Reports') ||
        auth()->user()->can('Manage Keyword Search Reports') ||
        auth()->user()->can('Manage Wishlist Reports'))
    <li style="padding-left: 0 !important;"
        class="hide-menu {{ Request::routeIs(['plugin.tlcommercecore.reports.search.keyword', 'plugin.tlcommercecore.reports.products.wishlist', 'plugin.tlcommercecore.reports.products']) ? 'active sub-menu-opened' : '' }}">
        <a href="#">
            <x-lucide-chart-pie style="width: 20px; height: 20px; margin-left: 8px;" />
            <span class="link-title ml-2">{{ translate('Reports') }}</span>
        </a>
        <ul class="nav sub-menu">
            @if (auth()->user()->can('Manage Product Reports'))
                <li class="{{ Request::routeIs(['plugin.tlcommercecore.reports.products']) ? 'active' : '' }}">
                    <a class="pl-2"
                        href="{{ route('plugin.tlcommercecore.reports.products') }}">{{ translate('Product Reports') }}</a>
                </li>
            @endif
            @if (auth()->user()->can('Manage Keyword Search Reports'))
                <li class="{{ Request::routeIs(['plugin.tlcommercecore.reports.search.keyword']) ? 'active' : '' }}">
                    <a class="pl-2"
                        href="{{ route('plugin.tlcommercecore.reports.search.keyword') }}">{{ translate('Keyword Search Reports') }}</a>
                </li>
            @endif
            @if (auth()->user()->can('Manage Wishlist Reports'))
                <li class="{{ Request::routeIs(['plugin.tlcommercecore.reports.products.wishlist']) ? 'active' : '' }}">
                    <a class="pl-2"
                        href="{{ route('plugin.tlcommercecore.reports.products.wishlist') }}">{{ translate('Wishlist Reports') }}</a>
                </li>
            @endif
        </ul>
    </li>
@endif
<!--End Report Module-->

<!--Shippings Module-->
@php
    $isactivateCarrier = isActivePluging('carrier');
    $shipping_carrier_active_link_file_links = [];
    $shipping_carrier_active_link_file =
        base_path() . '/plugins/carrier/views/includes/submenu/shipping_active_link.json';
    if (file_exists($shipping_carrier_active_link_file)) {
        $shipping_carrier_active_link_file_links = json_decode(
            file_get_contents($shipping_carrier_active_link_file),
            true,
        );
    }
    $isactivatePickupPoint = isActivePluging('pickuppoint');
    $shipping_pickup_point_active_link_file_links = [];
    $shipping_pickup_point_active_link_file =
        base_path() . '/plugins/pickuppoint/views/includes/submenu/shipping_active_link.json';
    if (file_exists($shipping_pickup_point_active_link_file)) {
        $shipping_pickup_point_active_link_file_links = json_decode(
            file_get_contents($shipping_pickup_point_active_link_file),
            true,
        );
    }
    $isactivateDeliveryBoy = isActivePluging('deliveryboy');
    $shipping_delivery_boy_active_link_file_links = [];
    $shipping_delivery_boy_active_link_file =
        base_path() . '/plugins/deliveryboy/views/includes/submenu/shipping_active_link.json';
    if (file_exists($shipping_delivery_boy_active_link_file)) {
        $shipping_delivery_boy_active_link_file_links = json_decode(
            file_get_contents($shipping_delivery_boy_active_link_file),
            true,
        );
    }
@endphp

@if (auth()->user()->can('Manage Shipping & Delivery') ||
        auth()->user()->can('Manage Pickup Points') ||
        auth()->user()->can('Manage Carriers') ||
        auth()->user()->can('Manage Locations'))
    <li style="padding-left: 0 !important;"
        class="hide-menu {{ Request::routeIs($shipping_carrier_active_link_file_links, $shipping_delivery_boy_active_link_file_links, $shipping_pickup_point_active_link_file_links, ['plugin.tlcommercecore.shipping.profile.manage', 'plugin.tlcommercecore.shipping.profile.form', 'plugin.tlcommercecore.shipping.configuration', 'plugin.tlcommercecore.shipping.locations.cities.edit', 'plugin.tlcommercecore.shipping.locations.cities.add.new', 'plugin.tlcommercecore.shipping.locations.cities.list', 'plugin.tlcommercecore.shipping.locations.states.edit', 'plugin.tlcommercecore.shipping.locations.states.new.add', 'plugin.tlcommercecore.shipping.locations.states.list', 'plugin.tlcommercecore.shipping.locations.country.edit', 'plugin.tlcommercecore.shipping.locations.country.new', 'plugin.tlcommercecore.shipping.locations.country.list']) ? 'active sub-menu-opened' : '' }}">
        <a href="#">
            <x-lucide-truck style="width: 20px; height: 20px; margin-left: 8px;" />
            <span class="link-title ml-2">{{ translate('Shippings') }}</span>
        </a>
        <ul class="nav sub-menu">
            @if (auth()->user()->can('Manage Shipping & Delivery'))
                <li class="{{ Request::routeIs(['plugin.tlcommercecore.shipping.configuration']) ? 'active' : '' }}">
                    <a class="pl-2"
                        href="{{ route('plugin.tlcommercecore.shipping.configuration') }}">{{ translate('Shipping & Delivery') }}</a>
                </li>
            @endif
            @if ($isactivateCarrier)
                 @if (auth()->user()->can('Manage Carriers'))
                     @includeIf('plugin/carrier::includes.submenu.shipping')
                 @endif
             @endif
            @if (auth()->user()->can('Manage Locations'))
                <li class="{{ Request::routeIs([
                    'plugin.tlcommercecore.shipping.locations.cities.edit',
                    'plugin.tlcommercecore.shipping.locations.cities.add.new',
                    'plugin.tlcommercecore.shipping.locations.cities.list',
                    'plugin.tlcommercecore.shipping.locations.states.edit',
                    'plugin.tlcommercecore.shipping.locations.states.new.add',
                    'plugin.tlcommercecore.shipping.locations.states.list',
                    'plugin.tlcommercecore.shipping.locations.country.edit',
                    'plugin.tlcommercecore.shipping.locations.country.new',
                    'plugin.tlcommercecore.shipping.locations.country.list',
                ]) ? 'sub-menu-opened' : '' }}">
                    <a class="pl-2" href="#">
                        <span class="link-title">{{ translate('Locations') }}</span>
                    </a>
                    <ul class="nav sub-menu">
                        <li class="{{ Request::routeIs(['plugin.tlcommercecore.shipping.locations.country.edit', 'plugin.tlcommercecore.shipping.locations.country.new', 'plugin.tlcommercecore.shipping.locations.country.list']) ? 'active' : '' }}">
                            <a class="pl-2"
                                href="{{ route('plugin.tlcommercecore.shipping.locations.country.list') }}">{{ translate('Countries') }}</a>
                        </li>
                        <li class="{{ Request::routeIs(['plugin.tlcommercecore.shipping.locations.states.edit', 'plugin.tlcommercecore.shipping.locations.states.new.add', 'plugin.tlcommercecore.shipping.locations.states.list']) ? 'active' : '' }}">
                            <a class="pl-2"
                                href="{{ route('plugin.tlcommercecore.shipping.locations.states.list') }}">{{ translate('States') }}</a>
                        </li>
                        <li class="{{ Request::routeIs(['plugin.tlcommercecore.shipping.locations.cities.edit', 'plugin.tlcommercecore.shipping.locations.cities.add.new', 'plugin.tlcommercecore.shipping.locations.cities.list']) ? 'active' : '' }}">
                            <a class="pl-2"
                                href="{{ route('plugin.tlcommercecore.shipping.locations.cities.list') }}">{{ translate('Cities') }}</a>
                        </li>
                    </ul>
                </li>
            @endif
        </ul>
    </li>
@endif
<!--End Shippings Module-->

<!--Payments Module-->
@if (auth()->user()->can('Manage Payment Methods') || auth()->user()->can('Manage Transaction history'))
    <li style="padding-left: 0 !important;"
        class="hide-menu {{ Request::routeIs(['plugin.tlcommercecore.payments.methods', 'plugin.tlcommercecore.payments.transactions.history']) ? 'active sub-menu-opened' : '' }}">
        <a href="#">
            <x-lucide-credit-card style="width: 20px; height: 20px; margin-left: 8px;" />
            <span class="link-title ml-2">{{ translate('Payments') }}</span>
        </a>
        <ul class="nav sub-menu">
            @if (auth()->user()->can('Manage Payment Methods'))
                <li class="{{ Request::routeIs(['plugin.tlcommercecore.payments.methods']) ? 'active' : '' }}">
                    <a class="pl-2"
                        href="{{ route('plugin.tlcommercecore.payments.methods') }}">{{ translate('Payment Methods') }}</a>
                </li>
            @endif
            @if (auth()->user()->can('Manage Transaction history'))
                <li class="{{ Request::routeIs(['plugin.tlcommercecore.payments.transactions.history']) ? 'active' : '' }}">
                    <a class="pl-2"
                        href="{{ route('plugin.tlcommercecore.payments.transactions.history') }}">{{ translate('Transaction history') }}</a>
                </li>
            @endif
        </ul>
    </li>
@endif
<!--End Payments Module-->

<style>
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

    @media (max-width: 900px) {
         .hide-menu {
            display: none !important;
        }
    }
</style>