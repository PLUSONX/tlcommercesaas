@php
    $order_repository = new Plugin\TlcommerceCore\Repositories\OrderRepository();

    $total_customers = \Plugin\TlcommerceCore\Models\Customers::select('id')->get()->count();

    $total_products = \Plugin\TlcommerceCore\Models\Product::select('id')->get()->count();

    $paidPaymentStatus = config('tlecommercecore.order_payment_status.paid');
    $cancelledDeliveryStatus = config('tlecommercecore.order_delivery_status.cancelled');

    $total_sales = \Plugin\TlcommerceCore\Models\Orders::where('payment_status', $paidPaymentStatus)
        ->where('delivery_status', '!=', $cancelledDeliveryStatus)
        ->sum('total_payable_amount');

    $recent_orders = \Plugin\TlcommerceCore\Models\Orders::with(['customer_info', 'guest_customer'])
    ->select('order_code', 'id', 'created_at', 'total_payable_amount', 'customer_id', 'guest_customer_id', 'delivery_status', 'payment_status')
    ->where('payment_status', $paidPaymentStatus)
    ->where('delivery_status', '!=', $cancelledDeliveryStatus)
    // ->where('delivery_status', config('tlecommercecore.order_delivery_status.delivered')) 
    ->orderBy('id', 'DESC')
    ->take(5)
    ->get();

    $paidOrdersConstraint = function ($query) use ($paidPaymentStatus, $cancelledDeliveryStatus) {
        $query->where('payment_status', $paidPaymentStatus)
            ->where('delivery_status', '!=', $cancelledDeliveryStatus);
    };

    $top_customers = \Plugin\TlcommerceCore\Models\Customers::with(['orders' => $paidOrdersConstraint])
        ->withCount(['orders as orders_count' => $paidOrdersConstraint])
        ->having('orders_count', '>', 0)
        ->orderBy('orders_count', 'DESC')
        ->take(5)
        ->get();

    $paidProductOrdersConstraint = function ($query) use ($paidPaymentStatus, $cancelledDeliveryStatus) {
        $query->where('payment_status', $paidPaymentStatus)
            ->where('delivery_status', '!=', $cancelledDeliveryStatus);
    };

    $top_products = \Plugin\TlcommerceCore\Models\Product::select(['id', 'name', 'permalink', 'thumbnail_image'])
        ->withCount(['orders' => $paidProductOrdersConstraint])
        ->withSum(['orders' => $paidProductOrdersConstraint], 'unit_price')
        ->withSum(['orders' => $paidProductOrdersConstraint], 'quantity')
        ->having('orders_count', '>', 0)
        ->orderBy('orders_sum_quantity', 'DESC')
        ->take(5)
        ->get();

    $category_data = [
        'tl_com_categories.id',
        DB::raw('GROUP_CONCAT(DISTINCT(tl_com_categories.icon)) as icon'),
        DB::raw('sum(tl_com_ordered_products.quantity * tl_com_ordered_products.unit_price) as total_sales'),
        DB::raw('sum(tl_com_ordered_products.quantity ) as number_of_sales'),
    ];
    $top_categories = DB::table('tl_com_categories')
        ->leftjoin('tl_com_product_has_categories', 'tl_com_product_has_categories.category_id', 'tl_com_categories.id')
        ->leftjoin('tl_com_ordered_products', 'tl_com_ordered_products.product_id', 'tl_com_product_has_categories.product_id')
        ->where('tl_com_ordered_products.payment_status', $paidPaymentStatus)
        ->where('tl_com_ordered_products.delivery_status', '!=', $cancelledDeliveryStatus)
        ->groupBy('tl_com_categories.id')
        ->select($category_data)
        ->orderBy(DB::raw('sum(tl_com_ordered_products.quantity )'), 'DESC')
        ->take(5)
        ->get();

    $top_categories = $top_categories->map(function ($category, $key) {
        $category_details = \Plugin\TlcommerceCore\Models\ProductCategory::select('id', 'name', 'permalink')->where('id', $category->id)->first();
        if ($category_details != null) {
            return [
                'id' => $category->id,
                'icon' => $category->icon,
                'name' => $category_details->translation('name', getLocale()),
                'total_sales' => $category->total_sales,
                'number_of_sales' => $category->number_of_sales,
            ];
        }
    });

    $brands_data = [
        'tl_com_brands.id',
        DB::raw('GROUP_CONCAT(DISTINCT(tl_com_brands.logo)) as logo'),
        DB::raw('sum(tl_com_ordered_products.quantity * tl_com_ordered_products.unit_price) as total_sales'),
        DB::raw('sum(tl_com_ordered_products.quantity ) as number_of_sales'),
    ];
    $top_brands = DB::table('tl_com_brands')
        ->leftjoin('tl_com_products', 'tl_com_products.brand', 'tl_com_brands.id')
        ->leftjoin('tl_com_ordered_products', 'tl_com_ordered_products.product_id', 'tl_com_products.id')
        ->where('tl_com_ordered_products.payment_status', $paidPaymentStatus)
        ->where('tl_com_ordered_products.delivery_status', '!=', $cancelledDeliveryStatus)
        ->groupBy('tl_com_brands.id')
        ->select($brands_data)
        ->orderBy(DB::raw('sum(tl_com_ordered_products.quantity )'), 'DESC')
        ->take(5)
        ->get();

    $top_brands = $top_brands->map(function ($brand, $key) {
        $brand_details = \Plugin\TlcommerceCore\Models\ProductBrand::select('id', 'name', 'permalink')->where('id', $brand->id)->first();
        if ($brand_details != null) {
            return [
                'id' => $brand->id,
                'logo' => $brand->logo,
                'name' => $brand_details->translation('name', getLocale()),
                'total_sales' => $brand->total_sales,
                'number_of_sales' => $brand->number_of_sales,
            ];
        }
    });

    $analytics_store_visits = 0;
    $analytics_add_to_cart = 0;
    $analytics_checkout = 0;
    $analytics_content_view = 0;

    try {
        $analytics_store_visits = \Plugin\TlcommerceCore\Models\AnalyticsEvent::where('event_type', 'store_visit')->count();
        $analytics_add_to_cart = \Plugin\TlcommerceCore\Models\AnalyticsEvent::where('event_type', 'add_to_cart')->count();
        $analytics_checkout = \Plugin\TlcommerceCore\Models\AnalyticsEvent::where('event_type', 'checkout')->count();
        $analytics_content_view = \Plugin\TlcommerceCore\Models\AnalyticsEvent::where('event_type', 'content_view')->count();
    } catch (\Throwable $e) {
        $analytics_store_visits = 0;
        $analytics_add_to_cart = 0;
        $analytics_checkout = 0;
        $analytics_content_view = 0;
    }

@endphp
@push('head')
    {{-- Push custom script or style into head tag --}}
    <style>
        .summary-card {
            /* background: url('/public/backend/assets/img/summery-bg1.png'); */
            background: url('backend/assets/img/summery-bg1.png');
            background-size: auto
        }

        .overflow-text {
            display: block;
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .dash-image {
            min-width: 60px !important;
        }

        .order-couter-item {
            padding: 13px 0px;
        }

        /* .apexcharts-toolbar {
                                        top: -30px !important;
                                    } */
        .apexcharts-toolbar {
            display: none !important;
        }

        .list-inline,
        .list-button {
            margin-right: 0px !important;
            padding-right: 0px !important;
        }

        .img-20 {
            width: 20px !important;
            height: 20px !important;
        }

        .analytics-breakdown-card {
            cursor: pointer;
            transition: box-shadow 0.15s ease, transform 0.15s ease;
        }

        .analytics-breakdown-card:hover {
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
            transform: translateY(-1px);
        }

        /* .glass-card {
                              position: relative;
                              border-radius: 18px;

                              /* Core glass layer */
        /* background: linear-gradient(
                                135deg,
                                rgba(255, 255, 255, 0.22),
                                rgba(255, 255, 255, 0.06)
                              );

                              backdrop-filter: blur(22px) saturate(180%);
                              -webkit-backdrop-filter: blur(22px) saturate(180%);

                              /* Thin glass edge */
        /* border: 1px solid rgba(255, 255, 255, 0.35); */
        /* Soft elevation */
        /* box-shadow:
                                0 20px 40px rgba(0, 0, 0, 0.25),
                                inset 0 1px 1px rgba(255, 255, 255, 0.35);

                              overflow: hidden;
                            }

                            .glass-card:hover {
                                scale: 1.05;
                              box-shadow:
                                0 30px 60px rgba(0, 0, 0, 0.35),
                                inset 0 1px 1px rgba(255, 255, 255, 0.45);
                            } */
        /* Customize date range picker */
        .customize-date-range {
            gap: 8px;
        }

        .customize-date-range input[type="date"] {
            height: 48px;
            padding: 0 12px;
            font-size: 14px;
            font-weight: 600;
            color: #333;
            background: #fff;
            border: 1px solid #dcdcdc;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        }

        .customize-date-range input[type="date"]:focus {
            outline: none;
            border-color: #ff5A1f;
            box-shadow: 0 0 0 3px rgba(255, 90, 31, 0.15);
        }

        .customize-date-range .apply-date-range {
            height: 48px;
            padding: 0 18px;
            border-radius: 10px;
            background-color: #ff5A1f;
            border-color: #ff5A1f;
            color: #fff;
            font-weight: 600;
            font-size: 14px;
        }

        .customize-date-range .apply-date-range:hover {
            background-color: #e64f1b;
            border-color: #e64f1b;
            color: #fff;
        }

        @media (max-width: 576px) {
            .customize-date-range {
                width: 100%;
                flex-wrap: wrap;
            }

            .customize-date-range input[type="date"] {
                flex: 1 1 45%;
            }

            .customize-date-range .apply-date-range {
                width: 100%;
            }
        }
    </style>
@endpush
@push('script')
    {{-- Push custom script or style bottom of body tag --}}
@endpush
<div class="row">

    <div class="dashboard-header d-sm-flex justify-content-between align-items-center ml-3 mr-3 w-100">
        <h4 class="font-20 mb-0">{{ translate('Dashboard') }}</h4>

        <div class="filter">
            <select name="filter" class="theme-input-style selectFilter">
                <option value="daily">Today</option>
                <option value="weekly">This week</option>
                <option value="monthly">This month</option>
                <option value="all-time" selected>All Time</option>
                <option value="customize">{{ translate('Customize') }}</option>
            </select>

            <div class="customize-date-range d-none align-items-center mt-2 mt-sm-0">
                <input type="date" class="filter-start-date" />
                <span>{{ translate('to') }}</span>
                <input type="date" class="filter-end-date" />
                <button type="button" class="btn apply-date-range">{{ translate('Apply') }}</button>
            </div>
        </div>
    </div>
    <!--<div class="col-xl-3 col-sm-6">
            <div class="card mb-30 bg-primary text-white glassmorphic-card">
                <div class="state">
                    <div class="align-items-center d-flex justify-content-center">
                        <div class="state-content text-center">
                            <p class="font-14 mb-2">Customers</p>
                            <h2>{{ $total_customers }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div> -->
    <!--Total Customers-->
    <!-- <div class="col-xl-3 col-sm-6">
        <div class="card mb-30 bg-primary text-white">
            <div class="state">
                <div class="align-items-center d-flex justify-content-center">
                    <div class="state-content text-center">
                        <p class="font-14 mb-2">{{ translate('Customers') }}</p>
                        <h2>{{ $total_customers }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div> -->

    <!--End total customers-->
    <!--Total Orders-->
    <!-- <div class="col-xl-3 col-sm-6">
        <div class="card mb-30 bg-info text-white">
            <div class="state">
                <div class="align-items-center d-flex justify-content-center">
                    <div class="state-content text-center">
                        <p class="font-14 mb-2">{{ translate('Orders') }}</p>
                        <h2>
                            {{ $order_repository->statusWiseOrderCounter(null, null, true) }}
                        </h2>
                    </div>
                </div>
            </div>
        </div>
    </div> -->
    <!--End total Orders-->
    <!--Total Products-->
    <!-- <div class="col-xl-3 col-sm-6">
        <div class="card mb-30 bg-danger text-white">
            <div class="state">
                <div class="align-items-center d-flex justify-content-center">
                    <div class="state-content text-center">
                        <p class="font-14 mb-2">{{ translate('Products') }}</p>
                        <h2>{{ $total_products }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div> -->
    <!--End Products-->
    <!--Total Sales-->
    <!-- <div class="col-xl-3 col-sm-6">
        <div class="card mb-30 bg-success text-white">
            <div class="state">
                <div class="align-items-center d-flex justify-content-center">
                    <div class="state-content text-center">
                        <p class="font-14 mb-2">{{ translate('Total Sales') }}</p>
                        <h2>{!! currencyExchange($total_sales) !!}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div> -->
    <!--End total Sales-->

    <div class="row w-100 mx-0 px-0 mt-20 align-items-start mb-2" style="padding-right: 0.5rem !important; padding-left: 0.5rem !important;">
        <div class="col px-1 mb-2">
            <div class="card bg-white text-black" style="height: 80%;  border-radius: 12px !important; overflow: hidden !important;">
                <div class="">
                    <div class="p-3 text-left">
                        <div class="d-flex align-items-center mb-4" style="gap: 10px;">
                            <x-lucide-users style="width: 22px; height: 22px; color: #ff5A1f;" />
                            <h4 class="mb-0" style="font-size: 18px; font-weight: 500;">{{ translate('Customers') }}</h4>
                        </div>
                        <!-- <h2 class="mb-0">{{ $total_customers }}</h2> -->
                        <h2 class="mb-0 total-customers-count">{{ $total_customers }}</h2>

                    </div>
                </div>
            </div>
        </div>

        <div class="col px-1 mb-2">
            <div class="card bg-white text-black" style="height: 80%;  border-radius: 12px !important; overflow: hidden !important;">
                <div class="">
                    <div class="p-3 text-left">
                        <div class="d-flex align-items-center mb-4" style="gap: 10px;">
                            <x-lucide-shopping-cart style="width: 22px; height: 22px; color: #ff5A1f;" />
                            <h4 class="mb-0" style="font-size: 18px; font-weight: 500;">{{ translate('Orders') }}</h4>
                        </div>
                        <!-- <h2 class="mb-0">{{ $order_repository->statusWiseOrderCounter(null, null, true) }}</h2> -->
                        <h2 class="mb-0 total-orders-count">{{ $order_repository->statusWiseOrderCounter(null, null, true) }}</h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="col px-1">
            <div class="card bg-white text-black" style="height: 80%;  border-radius: 12px !important; overflow: hidden !important;">
                <div class="">
                    <div class="p-3 text-left">
                        <div class="d-flex align-items-center mb-4" style="gap: 10px;">
                            <x-lucide-shopping-basket style="width: 22px; height: 22px; color: #ff5A1f;" />
                            <h4 class="mb-0" style="font-size: 18px; font-weight: 500;">{{ translate('Products') }}</h4>
                        </div>
                        <!-- <h2 class="mb-0">{{ $total_products }}</h2> -->
                        <h2 class="mb-0 total-products-count">{{ $total_products }}</h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="col px-1" style="padding-right: 10px;">
            <div class="card bg-white text-black" style="height: 80%;  border-radius: 12px !important; overflow: hidden !important;">
                <div class="">
                    <div class="p-3 text-left">
                        <div class="d-flex align-items-center mb-4" style="gap: 10px;">
                            <x-lucide-circle-dollar-sign style="width: 22px; height: 22px; color: #ff5A1f;" />
                            <h4 class="mb-0" style="font-size: 18px; font-weight: 500;">{{ translate('Sales') }}</h4>
                        </div>
                        <!-- <h2 class="mb-0">{{ currencyExchange($total_sales) }}</h2> -->
                        <h2 class="mb-0 total-sales-count">{{ currencyExchange($total_sales) }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row w-100 mx-0 px-0 mt-20 align-items-start mb-2" style="padding-right: 0.5rem !important; padding-left: 0.5rem !important;">
        <div class="col-6 col-md px-1 mb-2">
            <div class="card bg-white text-black" style="height: 80%;  border-radius: 12px !important; overflow: hidden !important;">
                <div class="">
                    <div class="p-3 text-left">
                        <div class="d-flex align-items-center mb-4" style="gap: 10px;">
                            <x-lucide-trending-up style="width: 22px; height: 22px; color: #ff5A1f;" />
                            <h4 class="mb-0" style="font-size: 18px; font-weight: 500;">{{ translate('Store Visits') }}</h4>
                        </div>
                        <!-- <h2 class="mb-0">{{ $analytics_store_visits }}</h2> -->
                        <h2 class="mb-0 analytics-store-visits-count">{{ $analytics_store_visits }}</h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md px-1 mb-2">
            <div class="card bg-white text-black analytics-breakdown-card" data-analytics-type="content_view" role="button" tabindex="0"
                style="height: 80%;  border-radius: 12px !important; overflow: hidden !important;">
                <div class="">
                    <div class="p-3 text-left">
                        <div class="d-flex align-items-center mb-4" style="gap: 10px;">
                            <x-lucide-view style="width: 22px; height: 22px; color: #ff5A1f;" />
                            <h4 class="mb-0" style="font-size: 18px; font-weight: 500;">{{ translate('Content View') }}</h4>
                        </div>
                        <!-- <h2 class="mb-0">{{ $analytics_content_view }}</h2> -->
                        <h2 class="mb-0 analytics-content-view-count">{{ $analytics_content_view }}</h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md px-1 mb-2">
            <div class="card bg-white text-black analytics-breakdown-card" data-analytics-type="add_to_cart" role="button" tabindex="0"
                style="height: 80%;  border-radius: 12px !important; overflow: hidden !important;">
                <div class="">
                    <div class="p-3 text-left">
                        <div class="d-flex align-items-center mb-4" style="gap: 10px;">
                            <x-lucide-shopping-cart style="width: 22px; height: 22px; color: #ff5A1f;" />
                            <h4 class="mb-0" style="font-size: 18px; font-weight: 500;">{{ translate('Add to Cart') }}</h4>
                        </div>
                        <!-- <h2 class="mb-0">{{ $analytics_add_to_cart }}</h2> -->
                        <h2 class="mb-0 analytics-add-to-cart-count">{{ $analytics_add_to_cart }}</h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md px-1" style="padding-right: 10px;">
            <div class="card bg-white text-black" style="height: 80%;  border-radius: 12px !important; overflow: hidden !important;">
                <div class="">
                    <div class="p-3 text-left">
                        <div class="d-flex align-items-center mb-4" style="gap: 10px;">
                            <x-lucide-shopping-basket style="width: 22px; height: 22px; color: #ff5A1f;" />
                            <h4 class="mb-0" style="font-size: 18px; font-weight: 500;">{{ translate('Checkout') }}</h4>
                        </div>
                        <!-- <h2 class="mb-0">{{ $analytics_checkout }}</h2> -->
                        <h2 class="mb-0 analytics-checkout-count">{{ $analytics_checkout }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="analytics-product-breakdown-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered" style="border-radius: 12px !important; overflow: hidden !important;">
            <div class="modal-content" style="border-radius: 12px !important;">
                <div class="modal-header">
                    <h4 class="modal-title h6 bold analytics-product-breakdown-title">{{ translate('Product breakdown') }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pt-0">
                    <p class="text-muted small mb-2 analytics-product-breakdown-status d-none"></p>
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead>
                                <tr>
                                    <th>{{ translate('Product') }}</th>
                                    <th class="text-right">{{ translate('Count') }}</th>
                                </tr>
                            </thead>
                            <tbody class="analytics-product-breakdown-body"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--Sales Reports-->
    <!-- <div class="col-xl-7 col-lg-7 col-12 mb-3" style="padding-right: 0px !important; margin-right: 0px !important; "> -->
    <div class="col-xl-7 col-lg-7 col-12 mb-3">
        <div class="card" style="border-radius: 12px !important; overflow: hidden !important;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start align-items-sm-center media">
                    <div class="d-flex justify-content-start justify-content-sm-between align-items-start align-items-sm-center flex-column flex-sm-row mb-sm-n3 media-body">
                        <!-- <div class="title-content mb-4 mr-sm-5 mb-sm-0"> -->
                        <div class="d-flex align-items-center title-content mb-4 mr-sm-5 mb-sm-0" style="gap: 10px;">
                            <x-lucide-circle-dollar-sign style="width: 22px; height: 22px; color: #ff5A1f;" />
                            <h4 class="">{{ translate('Sales Report') }}</h4>
                        </div>
                        <!-- List Button -->
                        <ul class="list-inline list-button m-0 mr-sm-4">
                            <li class="active chart-switcher" data-type="monthly">{{ translate('Monthly') }}</li>
                            <li class="chart-switcher" data-type="daily">{{ translate('Daily') }}</li>
                        </ul>
                        <!-- End List Button -->
                    </div>
                </div>
            </div>
            <div id="apex_sales_report_chart"></div>
        </div>
    </div>
    <!--End Sales Reports-->

    <!--Order Counter-->
    <!-- <div class="col-xl-4 col-lg-5 grid-item">
        <div class="card mb-30" style="border-radius: 12px !important; overflow: hidden !important;">
            <div class="card-body">

                <div class="d-flex align-items-center title-content mr-sm-5 mb-sm-0" style="gap: 10px; margin-bottom: 30px;">
                            <x-lucide-trending-up style="width: 22px; height: 22px; color: #ff8c00;" />
                            <h4 class="">{{ translate('Invoice Report') }}</h4>
                        </div>
                <div class="trans-history">
                    <div class="align-items-center border-bottom d-flex justify-content-between mb-2 order-couter-item">
                        <div class="d-flex align-items-center">
                            <div class="img mr-3">
                                <i class="icofont-list font-20"></i>
                            </div>
                            <div class="content">
                                <h5>{{ translate('Pending') }}</h5>
                            </div>
                        </div>
                        <div class="">
                            <h5>
                                {{ $order_repository->statusWiseOrderCounter(config('tlecommercecore.order_delivery_status.pending'), null, true) }}
                            </h5>
                        </div>
                    </div>
                    <div class="align-items-center border-bottom d-flex justify-content-between mb-2 order-couter-item">
                        <div class="d-flex align-items-center">
                            <div class="img mr-3">
                                <i class="icofont-tick-boxed font-20"></i>
                            </div>
                            <div class="content">
                                <h5>{{ translate('Approved') }}</h5>
                            </div>
                        </div>
                        <div class="">
                            <h5>
                                {{ $order_repository->statusWiseOrderCounter(config('tlecommercecore.order_delivery_status.processing'), null, true) }}
                            </h5>
                        </div>
                    </div>
                    
                    <div class="align-items-center border-bottom d-flex justify-content-between mb-2 order-couter-item">
                        <div class="d-flex align-items-center">
                            <div class="img mr-3">
                                <i class="icofont-box font-20"></i>
                            </div>
                            <div class="content">
                                <h5>{{ translate('Ready to Ship') }}</h5>
                            </div>
                        </div>
                        <div class="">
                            <h5>
                                {{ $order_repository->statusWiseOrderCounter(config('tlecommercecore.order_delivery_status.ready_to_ship'), null, true) }}
                            </h5>
                        </div>
                    </div>
                    
                    <div class="align-items-center border-bottom d-flex justify-content-between mb-2 order-couter-item">
                        <div class="d-flex align-items-center">
                            <div class="img mr-3">
                                <i class="icofont-fast-delivery font-20"></i>
                            </div>
                            <div class="content">
                                <h5>{{ translate('Shipped') }}</h5>
                            </div>
                        </div>
                        <div class="">
                            <h5>
                                {{ $order_repository->statusWiseOrderCounter(config('tlecommercecore.order_delivery_status.shipped'), null, true) }}
                            </h5>
                        </div>
                    </div>
                    
                    <div class="align-items-center border-bottom d-flex justify-content-between mb-2 order-couter-item">
                        <div class="d-flex align-items-center">
                            <div class="img mr-3">
                                <i class="icofont-tick-mark font-20"></i>
                            </div>
                            <div class="content">
                                <h5>{{ translate('Delivered') }}</h5>
                            </div>
                        </div>
                        <div class="">
                            <h5>
                                {{ $order_repository->statusWiseOrderCounter(config('tlecommercecore.order_delivery_status.delivered'), null, true) }}
                            </h5>
                        </div>
                    </div>
                    
                    <div class="align-items-center border-bottom d-flex justify-content-between mb-2 order-couter-item">
                        <div class="d-flex align-items-center">
                            <div class="img mr-3">
                                <i class="icofont-close font-20"></i>
                            </div>
                            <div class="content">
                                <h5>{{ translate('Cancelled') }}</h5>
                            </div>
                        </div>
                        <div class="">
                            <h5>
                                {{ $order_repository->statusWiseOrderCounter(config('tlecommercecore.order_delivery_status.cancelled'), null, true) }}
                            </h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> -->

    <div class="col-xl-5 col-lg-5 grid-item mb-3" style=" margin-left: 0px !important; ">
        <div class="card" style="border-radius: 12px !important; overflow: hidden !important;">
            <div class="card-body">

                {{-- Title --}}
                <div class="d-flex align-items-center mr-sm-5 mb-sm-0" style="gap: 10px; margin-bottom: 36px !important;">
                    <x-lucide-trending-up style="width: 22px; height: 22px; color: #ff5A1f;" />
                    <h4 class="mb-0">{{ translate('Invoice Report') }}</h4>
                </div>

                {{-- Doughnut Chart --}}
                <div class="d-flex justify-content-center align-items-center" style="position: relative; ">
                    <div id="orderStatusChart"></div>
                    <!-- <div style="position: absolute; text-align: center; pointer-events: none;">
                        <p class="mb-0" style="font-size: 12px; color: #888;">{{ translate('Total Sales') }}</p>
                        <h5 class="mb-0" style="font-size: 15px; font-weight: 600;">{!! currencyExchange($total_sales) !!}</h5>
                    </div> -->
                </div>

                {{-- Status Table --}}
                <table class="w-100" style="border-collapse: collapse; background: transparent;">
                    <tbody>
                        <tr>

                            <td class="py-1 px-2">
                                <span class="d-flex align-items-center justify-content-between w-100">

                                    <span class="d-flex align-items-center" style="gap: 6px;">
                                        <span style="width: 10px; height: 10px; border-radius: 50%; background: #f6c23e; display:inline-block;"></span>
                                        {{ translate('Pending') }}:
                                    </span>

                                    <strong class="ml-2">
                                        {{ $order_repository->statusWiseOrderCounter(config('tlecommercecore.order_delivery_status.pending'), null, true) }}
                                    </strong>

                                </span>
                            </td>
                            <td class="py-1 px-2">
                                <span class="d-flex align-items-center justify-content-between w-100">

                                    <span class="d-flex align-items-center" style="gap: 6px;">
                                        <span style="width: 10px; height: 10px; border-radius: 50%; background: #36b9cc; display:inline-block;"></span>
                                        {{ translate('Approved') }}:
                                    </span>

                                    <strong class="ml-2">
                                        {{ $order_repository->statusWiseOrderCounter(config('tlecommercecore.order_delivery_status.processing'), null, true) }}
                                    </strong>

                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="py-1 px-2">

                                <span class="d-flex align-items-center justify-content-between w-100">

                                    <span class="d-flex align-items-center" style="gap: 6px;">
                                        <span style="width: 10px; height: 10px; border-radius: 50%; background: #ff8c00; display:inline-block;"></span>
                                        {{ translate('Ready to Ship') }}:
                                    </span>

                                    <strong
                                        class="ml-2">{{ $order_repository->statusWiseOrderCounter(config('tlecommercecore.order_delivery_status.ready_to_ship'), null, true) }}</strong>

                                </span>
                            </td>
                            <td class="py-1 px-2">
                                <span class="d-flex align-items-center justify-content-between w-100">

                                    <span class="d-flex align-items-center" style="gap: 6px;">
                                        <span style="width: 10px; height: 10px; border-radius: 50%; background: #4e73df; display:inline-block;"></span>
                                        {{ translate('Shipped') }}:
                                    </span>
                                    <strong>{{ $order_repository->statusWiseOrderCounter(config('tlecommercecore.order_delivery_status.shipped'), null, true) }}</strong>
                                </span>

                            </td>
                        </tr>
                        <tr>
                            <td class="py-1 px-2">
                                <span class="d-flex align-items-center justify-content-between w-100">
                                    <span class="d-flex align-items-center" style="gap: 6px;">
                                        <span style="width: 10px; height: 10px; border-radius: 50%; background: #1cc88a; display:inline-block;"></span>
                                        {{ translate('Delivered') }}:
                                    </span>
                                    <strong>{{ $order_repository->statusWiseOrderCounter(config('tlecommercecore.order_delivery_status.delivered'), null, true) }}</strong>
                                </span>
                            </td>
                            <td class="py-1 px-2">
                                <span class="d-flex align-items-center justify-content-between w-100">
                                    <span class="d-flex align-items-center" style="gap: 6px;">
                                        <span style="width: 10px; height: 10px; border-radius: 50%; background: #e74a3b; display:inline-block;"></span>
                                        {{ translate('Cancelled') }}:
                                    </span>
                                    <strong>{{ $order_repository->statusWiseOrderCounter(config('tlecommercecore.order_delivery_status.cancelled'), null, true) }}</strong>
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>

            </div>
        </div>
    </div>

    <!--End order counter-->
    <!--Recent Orders-->
    <!-- <div class="col-xl-8 col-lg-7 col-12 mb-20"> -->
    <div class="col-xl-8 col-12 mb-3">
        <!-- Card -->
        <div class="card" style="border-radius: 12px !important; overflow: hidden !important;">
            <div class="card-body pb-0">
                <div class="d-flex justify-content-between">
                    <div class="d-flex align-items-center mr-sm-5 mb-sm-0" style="gap: 10px;">
                        <x-lucide-trending-up style="width: 22px; height: 22px; color: #ff5A1f;" />
                        <h4 class="mb-0">{{ translate('Recent Orders') }}</h4>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="style--three table-centered text-nowrap">
                    <thead>
                        <tr>
                            <th>{{ translate('Order ID') }}</th>
                            <th class="pl-5">{{ translate('Date') }}</th>
                            <th>{{ translate('Customer') }}</th>
                            <th class="text-center">{{ translate('Total Amount') }}</th>
                            <th class="text-center">{{ translate('Delivery Status') }}</th>
                            <!-- <th class="text-center">{{ translate('Payment Status') }}</th> -->
                            <th class="text-center">{{ translate('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($recent_orders->count() > 0)
                            @foreach ($recent_orders as $order)
                                <tr>
                                    <td>
                                        <a href="{{ route('plugin.tlcommercecore.orders.details', ['id' => $order->id]) }}">{{ $order->order_code }}</a>
                                    </td>
                                    <td>{{ $order->created_at->format('d M Y h:i A') }}</td>
                                    <td>
                                        @if ($order->customer_info != null)
                                            <a
                                                href="{{ route('plugin.tlcommercecore.customers.details', ['id' => $order->customer_id]) }}">{{ $order->customer_info->name }}</a>
                                        @else
                                            <a href="#">{{ $order->guest_customer?->name ?? 'Guest' }}
                                                <span class="badge" style="background-color: #ff5A1f; color: #fff;">
                                                    {{ translate('Guest') }}
                                                </span>
                                            </a>
                                        @endif
                                    </td>
                                    <td class="text-center">{!! currencyExchange($order->total_payable_amount) !!}</td>
                                    <td class="text-center">
                                        <span class="badge badge-success">{{ translate('Delivered') }}</span>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('plugin.tlcommercecore.orders.details', ['id' => $order->id]) }}" class="details-btn"
                                            style="color: #ff5A1f !important;">
                                            Details
                                            <i class="icofont-arrow-right"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="5">
                                    <p class="alert alert-danger text-center">{{ translate('Nothing found') }}</p>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
        <!-- End Card -->
    </div>
    <!--End recents orders-->
    <!--Top customers-->
    <!-- <div class="col-xl-3 col-lg-6 " style="padding-right: 0px !important; margin-right: 0px !important; "> -->
    <div class="col-xl-3 col-lg-6">
        <div class="card mb-20" style="border-radius: 12px !important; overflow: hidden !important;">
            <div class="card-body">
                <div class="d-flex justify-content-between mb-3">
                    <div class="d-flex align-items-center mr-sm-5 mb-sm-0" style="gap: 10px;">
                        <x-lucide-trending-up style="width: 22px; height: 22px; color: #ff5A1f;" />
                        <h4 class="mb-1">{{ translate('Top Customers') }}</h4>
                    </div>
                </div>
                <!-- <div class="d-flex align-items-start align-items-sm-end justify-content-between mb-3">
                    <div class="">
                        <h4 class="mb-1">{{ translate('Top Customers') }}</h4>
                    </div>
                </div> -->
                <div class="product-list">
                    @if ($top_customers->count() > 0)
                        @foreach ($top_customers as $customer)
                            <div class="product-list-item mb-20 d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <div class="img mr-3">
                                        <img src="{{ $customer->image ? str_replace('/public', '', getFilePath($customer->image)) : asset('backend/assets/img/avatar/avatar-user.png') }}"
                                            alt="Customer Image" />
                                        <!-- <img src="{{ asset(getFilePath($customer->image, true)) }}"
                                            alt="{{ $customer->name }}"> -->
                                    </div>
                                    <div class="content">
                                        <a href="{{ route('plugin.tlcommercecore.customers.details', ['id' => $customer->id]) }}" class="black mb-1">{{ $customer->name }}
                                        </a>
                                        <p class="c3 bold font-14">
                                            {!! currencyExchange($customer->orders->sum('total_payable_amount')) !!}</p>
                                    </div>
                                </div>
                                <p class="font-14">{{ $customer->orders_count }}
                                    {{ $customer->orders_count > 1 ? 'Orders' : 'Order' }}</p>
                            </div>
                        @endforeach
                    @else
                        <p class="alert alert-danger text-center">{{ translate('Nothing Found') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!--End Top Customers-->
    <!--Top Products-->
    <div class="col-xl-4 col-lg-6">
        <!-- Card -->
        <div class="card mb-30" style="border-radius: 12px !important; overflow: hidden !important;">
            <div class="card-body">
                <!-- <div class="d-flex align-items-start align-items-sm-end justify-content-between mb-3">
                    <div class="">
                        <h4 class="mb-1">{{ translate('Top Products') }}</h4>
                    </div>
                </div> -->
                <div class="d-flex justify-content-between mb-3">
                    <div class="d-flex align-items-center mr-sm-5 mb-sm-0" style="gap: 10px;">
                        <x-lucide-trending-up style="width: 22px; height: 22px; color: #ff5A1f;" />
                        <h4 class="mb-1">{{ translate('Top Products') }}</h4>
                    </div>
                </div>
                <div class="product-list">
                    @if ($top_products->count() > 0)
                        @foreach ($top_products as $product)
                            <div class="product-list-item mb-20 d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <div class="img mr-3">

                                        <img src="{{ $product->thumbnail_image ? str_replace('/public', '', getFilePath($product->thumbnail_image)) : asset('backend/assets/img/avatar/avatar-user.png') }}"
                                            alt="Customer Image" />
                                        <!-- <img src="{{ asset(getFilePath($product->thumbnail_image)) }}"
                                            alt="{{ $product->translation('name', getLocale()) }}"
                                            class="dash-image"> -->
                                    </div>
                                    <div class="content">
                                        <p class="black mb-1 overflow-text text-capitalize">
                                            {{ $product->translation('name', getLocale()) }}</p>
                                        <span class="c3 bold font-14">{!! currencyExchange($product->orders_sum_unit_price * $product->orders_sum_quantity) !!}</span>
                                    </div>
                                </div>
                                <p class="font-14">
                                    {{ $product->orders_count != null ? $product->orders_count : 0 }}
                                    {{ $product->orders_count > 1 ? 'Sales' : 'Sale' }}</p>
                            </div>
                        @endforeach
                    @else
                        <p class="alert alert-danger text-center">{{ translate('Nothing Found') }}</p>
                    @endif
                </div>
            </div>
        </div>
        <!-- End Card -->
    </div>
    <!--End Top Products-->
    <!--Top Categories-->
    <!-- <div class="col-xl-4 col-lg-6">
        <div class="card mb-30">
            <div class="card-body">
                <div class="d-flex align-items-start align-items-sm-end justify-content-between mb-3">
                    <div class="">
                        <h4 class="mb-1">{{ translate('Top Categories') }}</h4>
                    </div>
                </div>
                <div class="product-list">
                    @if ($top_categories->count() > 0)
@foreach ($top_categories as $category)
<div class="product-list-item mb-20 d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <div class="img mr-3">
                                         <img src="{{ str_replace('/public', '', asset(getFilePath($category['icon'], true))) }}"
                                            alt="{{ $category['name'] }}">
                                    </div>
                                    <div class="content">
                                        <p class="black mb-1">{{ $category['name'] }}</p>
                                        <span class="c3 bold font-14">{!! currencyExchange($category['total_sales']) !!}</span>
                                    </div>
                                </div>
                                <p class="font-14">
                                    {{ $category['number_of_sales'] != null ? $category['number_of_sales'] : 0 }}
                                    {{ $category['number_of_sales'] > 1 ? 'Sales' : 'Sale' }}</p>
                            </div>
@endforeach
@else
<p class="alert alert-danger text-center">{{ translate('Nothing Found') }}</p>
@endif
                </div>
            </div>
        </div>
    </div> -->
    <!--End Top Categories-->
    <!--Top Brands-->
    <!-- <div class="col-xl-4 col-lg-6">
        <div class="card mb-30">
            <div class="card-body">
                <div class="d-flex align-items-start align-items-sm-end justify-content-between mb-3">
                    <div class="">
                        <h4 class="mb-1">{{ translate('Top Brands') }}</h4>
                    </div>
                </div>
                <div class="product-list">
                    @if ($top_brands->count() > 0)
@foreach ($top_brands as $brand)
<div class="product-list-item mb-20 d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <div class="img mr-3">
                                         <img src="{{ str_replace('/public', '', asset(getFilePath($brand['logo']))) }}"
                                            alt="{{ $brand['name'] }}" class="img-20">
                                    </div>
                                    <div class="content">
                                        <p class="black mb-1">{{ $brand['name'] }}</p>
                                        <span class="c3 bold font-14">{!! currencyExchange($brand['total_sales']) !!}</span>
                                    </div>
                                </div>
                                <p class="font-14">
                                    {{ $brand['number_of_sales'] != null ? $brand['number_of_sales'] : 0 }}
                                    {{ $brand['number_of_sales'] > 1 ? 'Sales' : 'Sale' }}</p>
                            </div>
@endforeach
@else
<p class="alert alert-danger text-center">{{ translate('Nothing Found') }}</p>
@endif
                </div>
            </div>
        </div>
    </div> -->
    <!--End Top Brands-->
</div>

@push('script')
    <script>
        (function($) {
            "use strict";
            let chart_data_type = "monthly";
            let categories = [];
            // Restrict date pickers to not allow future dates
            const todayStr = new Date().toISOString().split('T')[0];
            $('.filter-start-date, .filter-end-date').attr('max', todayStr);

            $('.selectFilter').on('change', function() {
                const filter = $(this).val();

                if (filter === 'customize') {
                    $('.customize-date-range').removeClass('d-none').addClass('d-flex');
                    return;
                }

                $('.customize-date-range').removeClass('d-flex').addClass('d-none');
                $('.filter-start-date, .filter-end-date').val('');

                $.ajax({
                    url: '{{ route('dashboard.filter') }}',
                    data: {
                        filter: filter
                    },
                    success: function(data) {
                        console.log("customers: ", data.total_customers);
                        console.log(typeof data.total_customers);
                        $('.total-customers-count').text(data.total_customers);
                        $('.total-products-count').text(data.total_products ?? 0);
                        $('.total-sales-count').text(data.total_sales ?? 0);
                        $('.total-orders-count').text(data.total_orders ?? 0);
                        $('.analytics-store-visits-count').text(data.analytics_store_visits ?? 0);
                        $('.analytics-add-to-cart-count').text(data.analytics_add_to_cart ?? 0);
                        $('.analytics-checkout-count').text(data.analytics_checkout ?? 0);
                        $('.analytics-content-view-count').text(data.analytics_content_view ?? 0);
                    },
                    error: function(err) {
                        console.error('Filter error:', err);
                    }
                });
            });

            $(document).on('click', '.apply-date-range', function() {
                const startDate = $('.filter-start-date').val();
                const endDate = $('.filter-end-date').val();

                if (!startDate || !endDate) {
                    alert('{{ translate('Please select both a start and end date') }}');
                    return;
                }

                if (startDate > endDate) {
                    alert('{{ translate('Start date cannot be after end date') }}');
                    return;
                }

                $.ajax({
                    url: '{{ route('dashboard.filter') }}',
                    data: {
                        filter: 'customize',
                        start_date: startDate,
                        end_date: endDate
                    },
                    success: function(data) {
                        $('.total-customers-count').text(data.total_customers ?? 0);
                        $('.total-products-count').text(data.total_products ?? 0);
                        $('.total-sales-count').text(data.total_sales ?? 0);
                        $('.total-orders-count').text(data.total_orders ?? 0);
                        $('.analytics-store-visits-count').text(data.analytics_store_visits ?? 0);
                        $('.analytics-add-to-cart-count').text(data.analytics_add_to_cart ?? 0);
                        $('.analytics-checkout-count').text(data.analytics_checkout ?? 0);
                        $('.analytics-content-view-count').text(data.analytics_content_view ?? 0);
                    },
                    error: function(err) {
                        console.error('Filter error:', err);
                    }
                });
            });

            function openAnalyticsBreakdown(eventType) {
                const titles = {
                    content_view: '{{ translate('Content View by Product') }}',
                    add_to_cart: '{{ translate('Add to Cart by Product') }}'
                };
                const $modal = $('#analytics-product-breakdown-modal');
                const $body = $modal.find('.analytics-product-breakdown-body');
                const $status = $modal.find('.analytics-product-breakdown-status');
                const filter = $('.selectFilter').val() || 'all-time';

                $modal.find('.analytics-product-breakdown-title').text(titles[eventType] || '{{ translate('Product breakdown') }}');
                $status.removeClass('d-none').text('{{ translate('Loading') }}...');
                $body.empty();
                $modal.modal('show');

                $.ajax({
                    url: '{{ route('dashboard.analytics.by.product') }}',
                    data: {
                        event_type: eventType,
                        filter: filter
                    },
                    success: function(data) {
                        const items = (data && data.items) ? data.items : [];
                        $body.empty();
                        if (!items.length) {
                            $status.removeClass('d-none').text('{{ translate('No data') }}');
                            return;
                        }
                        $status.addClass('d-none').text('');
                        items.forEach(function(item) {
                            const name = $('<div>').text(item.name || ('#' + item.product_id)).html();
                            const image = $('<div>').text(item.image || '{{ asset('backend/assets/img/avatar/avatar-user.png') }}').html();
                            const total = item.total ?? 0;
                            $body.append(
                                '<tr><td>' +
                                '<div class="d-flex align-items-center" style="gap: 10px;">' +
                                '<img src="' + image +
                                '" alt="" style="width: 40px; height: 40px; object-fit: cover; border-radius: 6px; flex-shrink: 0;" />' +
                                '<span>' + name + '</span>' +
                                '</div>' +
                                '</td><td class="text-right">' + total + '</td></tr>'
                            );
                        });
                    },
                    error: function() {
                        $status.removeClass('d-none').text('{{ translate('No data') }}');
                        $body.empty();
                    }
                });
            }

            $(document).on('click', '.analytics-breakdown-card', function() {
                const eventType = $(this).data('analytics-type');
                if (!eventType) {
                    return;
                }
                openAnalyticsBreakdown(eventType);
            });

            $(document).on('keyup', '.analytics-breakdown-card', function(e) {
                if (e.key !== 'Enter' && e.key !== ' ') {
                    return;
                }
                e.preventDefault();
                const eventType = $(this).data('analytics-type');
                if (!eventType) {
                    return;
                }
                openAnalyticsBreakdown(eventType);
            });

            // function dateFormatted() {
            //     let today = new Date();

            //     let year = today.getFullYear();
            //     let month = String(today.getMonth() + 1).padStart(2, '0');
            //     let day = String(today.getDate()).padStart(2, '0');

            //     let todayFormatted = `${year}-${month}-${day}`;

            //     return todayFormatted;

            // }

            // function loadDailyData() {
            //     console.log("Daily selected");
            //     let today = dateFormatted();

            //     console.log(today);
            // }

            //  function loadWeeklyData() {
            //     console.log("Weekly selected");
            // }

            // function loadMonthlyData() {
            //     console.log("Monthly selected");
            // }

            // function loadAllTimeData() {
            //     console.log("All Time selected");
            // }

            $(".chart-switcher").on('click', function(e) {
                e.preventDefault();
                $('.chart-switcher').removeClass('active');
                $(this).addClass('active');
                chart_data_type = $(this).data('type');
                getChartData();
            });

            function getChartData() {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    },
                    type: "POST",
                    data: {
                        type: chart_data_type
                    },
                    url: '{{ route('plugin.tlcommercecore.reports.sales.chart') }}',
                    success: function(data) {
                        if (data.success) {
                            categories = data.times;
                            sales_chart.updateSeries([{
                                name: 'Sales',
                                data: data.sales
                            }]);
                            sales_chart.updateOptions({
                                xaxis: {
                                    categories: data.times
                                }
                            });
                        }
                    }
                });
            }

            var sales_chart_options = {
                series: [],
                chart: {
                    height: 340,
                    type: 'line',
                    toolbar: {
                        show: true
                    },
                    zoom: {
                        enabled: false
                    }
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'smooth',
                    width: 3,
                    dashArray: 0
                },
                colors: ['#FFBA5A', '#8381FD'],
                grid: {
                    borderColor: '#f5f5f5'
                },
                markers: {
                    size: 7,
                    colors: ["#ff5A1f"],
                    hover: {
                        size: 8
                    }
                },
                xaxis: {
                    categories: []
                },
                yaxis: {
                    tickAmount: 4,
                    labels: {
                        formatter: function(val) {
                            return val.toFixed(2);
                        }
                    }
                },
                responsive: [{
                    breakpoint: 576,
                    options: {
                        markers: {
                            size: 5,
                            colors: ["#ff5A1f"],
                            hover: {
                                size: 5
                            }
                        }
                    }
                }]
            };

            var sales_chart = new ApexCharts(document.querySelector("#apex_sales_report_chart"), sales_chart_options);
            sales_chart.render();

            // Doughnut chart using ApexCharts
            var donut_options = {
                series: [
                    {{ $order_repository->statusWiseOrderCounter(config('tlecommercecore.order_delivery_status.processing'), null, true) }},
                    {{ $order_repository->statusWiseOrderCounter(config('tlecommercecore.order_delivery_status.ready_to_ship'), null, true) }},
                    {{ $order_repository->statusWiseOrderCounter(config('tlecommercecore.order_delivery_status.shipped'), null, true) }},
                    {{ $order_repository->statusWiseOrderCounter(config('tlecommercecore.order_delivery_status.delivered'), null, true) }},
                    {{ $order_repository->statusWiseOrderCounter(config('tlecommercecore.order_delivery_status.cancelled'), null, true) }}
                ],
                chart: {
                    type: 'donut',
                    height: 220,
                },
                labels: ['Approved', 'Ready to Ship', 'Shipped', 'Delivered', 'Cancelled'],
                colors: ['#36b9cc', '#ff8c00', '#4e73df', '#1cc88a', '#e74a3b'],
                plotOptions: {
                    pie: {
                        donut: {
                            size: '65%',
                            labels: {
                                show: true,
                                total: {
                                    show: true,
                                    label: '{{ translate("Total Sales") }}',
                                    formatter: function() {
                                        return '{!! addslashes(currencyExchange($total_sales)) !!}';
                                    }
                                }
                            }
                        }
                    }
                },
                legend: {
                    show: false
                },
                dataLabels: {
                    enabled: false
                },
            };

            var donut_chart = new ApexCharts(document.querySelector("#orderStatusChart"), donut_options);
            donut_chart.render();

            $(document).ready(function() {
                getChartData();
            });

        })(jQuery);
    </script>
@endpush

<style>
    /* Change the background and text color of the active button */
    .list-button li.active.chart-switcher {
        background-color: #ff5A1f !important;
        color: #ffffff !important;
        border-color: #ff5A1f !important;
        /* In case there is a border */
    }

    /* Optional: If you want a slight hover effect on the inactive buttons */
    .list-button li.chart-switcher:hover:not(.active) {
        background-color: #ff5A1f !important;
        color: #ffffff !important;
        cursor: pointer;
    }

    .filter {
        position: relative;
        display: inline-block;
        min-width: 180px;
    }

    .selectFilter {
        width: 100%;
        height: 48px;
        line-height: 48px;
        padding: 0 40px 0 15px;
        font-size: 14px;
        font-weight: 600;
        color: #333;
        background: #fff;
        border: 1px solid #dcdcdc;
        border-radius: 10px;
        cursor: pointer;
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        transition: all 0.3s ease;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
    }

    .selectFilter option {
        padding: 10px;
        line-height: normal;
    }

    /* Hover */
    .selectFilter:hover {
        border-color: #ff5A1f;
    }

    /* Focus */
    .selectFilter:focus {
        outline: none;
        border-color: #ff5A1f;
        box-shadow: 0 0 0 3px rgba(108, 92, 231, 0.15);
    }

    /* Custom arrow */
    .filter::after {
        content: "▼";
        font-size: 11px;
        color: #666;
        position: absolute;
        right: 14px;
        top: 50%;
        transform: translateY(-50%);
        pointer-events: none;
    }

    @media (max-width: 576px) {
        .dashboard-header {
            flex-direction: column;
            align-items: stretch !important;
            gap: 12px;
        }

        .dashboard-header h4 {
            text-align: center;
            white-space: normal;
            margin-bottom: 20px !important;
        }

        .filter {
            width: 100%;
            min-width: unset;
        }

        .selectFilter {
            width: 100%;
        }
    }
</style>
