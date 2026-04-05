@php
    $order_repository = new Plugin\TlcommerceCore\Repositories\OrderRepository();
    $total_customers = \Plugin\TlcommerceCore\Models\Customers::select('id')
        ->get()
        ->count();
    $total_products = \Plugin\TlcommerceCore\Models\Product::select('id')
        ->get()
        ->count();
    $total_sales = \Plugin\TlcommerceCore\Models\Orders::sum('total_payable_amount');
    
    $recent_orders = \Plugin\TlcommerceCore\Models\Orders::with(['customer_info', 'guest_customer'])
        ->select('order_code', 'id', 'created_at', 'total_payable_amount', 'customer_id', 'delivery_status', 'payment_status')
        ->orderBy('id', 'DESC')
        ->take(5)
        ->get();

    Illuminate\Support\Facades\Log::info('Orders data', [
        'recent_orders' => $recent_orders->toArray()
    ]);
    
    $top_customers = \Plugin\TlcommerceCore\Models\Customers::withCount('orders')
        ->orderBy('orders_count', 'DESC')
        ->take(5)
        ->get();
    
    $top_products = \Plugin\TlcommerceCore\Models\Product::select(['id', 'name', 'permalink', 'thumbnail_image'])
        ->withCount(['orders'])
        ->withSum('orders', 'unit_price')
        ->withSum('orders', 'quantity')
        ->orderBy('orders_sum_quantity', 'DESC')
        ->take(5)
        ->get();
    
    $category_data = ['tl_com_categories.id', DB::raw('GROUP_CONCAT(DISTINCT(tl_com_categories.icon)) as icon'), DB::raw('sum(tl_com_ordered_products.quantity * tl_com_ordered_products.unit_price) as total_sales'), DB::raw('sum(tl_com_ordered_products.quantity ) as number_of_sales')];
    $top_categories = DB::table('tl_com_categories')
        ->leftjoin('tl_com_product_has_categories', 'tl_com_product_has_categories.category_id', 'tl_com_categories.id')
        ->leftjoin('tl_com_ordered_products', 'tl_com_ordered_products.product_id', 'tl_com_product_has_categories.product_id')
        ->groupBy('tl_com_categories.id')
        ->select($category_data)
        ->orderBy(DB::raw('sum(tl_com_ordered_products.quantity )'), 'DESC')
        ->take(5)
        ->get();
    
    $top_categories = $top_categories->map(function ($category, $key) {
        $category_details = \Plugin\TlcommerceCore\Models\ProductCategory::select('id', 'name', 'permalink')
            ->where('id', $category->id)
            ->first();
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
    
    $brands_data = ['tl_com_brands.id', DB::raw('GROUP_CONCAT(DISTINCT(tl_com_brands.logo)) as logo'), DB::raw('sum(tl_com_ordered_products.quantity * tl_com_ordered_products.unit_price) as total_sales'), DB::raw('sum(tl_com_ordered_products.quantity ) as number_of_sales')];
    $top_brands = DB::table('tl_com_brands')
        ->leftjoin('tl_com_products', 'tl_com_products.brand', 'tl_com_brands.id')
        ->leftjoin('tl_com_ordered_products', 'tl_com_ordered_products.product_id', 'tl_com_products.id')
        ->groupBy('tl_com_brands.id')
        ->select($brands_data)
        ->orderBy(DB::raw('sum(tl_com_ordered_products.quantity )'), 'DESC')
        ->take(5)
        ->get();
    
    $top_brands = $top_brands->map(function ($brand, $key) {
        $brand_details = \Plugin\TlcommerceCore\Models\ProductBrand::select('id', 'name', 'permalink')
            ->where('id', $brand->id)
            ->first();
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

        .list-inline, .list-button {
            margin-right: 0px !important;
            padding-right: 0px !important;
        }

        .img-20 {
            width: 20px !important;
            height: 20px !important;
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
    </style>
@endpush
@push('script')
    {{-- Push custom script or style bottom of body tag --}}
@endpush
 <div class="row">

    <div class="d-sm-flex justify-content-between align-items-center ml-3">
        <h4 class="font-20">{{ translate('Dashboard') }}</h4>
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
                            {{ $order_repository->statusWiseOrderCounter() }}
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
                        <h2 class="mb-0">{{ $total_customers }}</h2>
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
                            <h2 class="mb-0">{{ $order_repository->statusWiseOrderCounter() }}</h2>
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
                            <h2 class="mb-0">{{ $total_products }}</h2>
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
                            <h2 class="mb-0">{{ currencyExchange($total_sales) }}</h2>
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
                    <div
                        class="d-flex justify-content-start justify-content-sm-between align-items-start align-items-sm-center flex-column flex-sm-row mb-sm-n3 media-body">
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
                                {{ $order_repository->statusWiseOrderCounter(config('tlecommercecore.order_delivery_status.pending')) }}
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
                                {{ $order_repository->statusWiseOrderCounter(config('tlecommercecore.order_delivery_status.processing')) }}
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
                                {{ $order_repository->statusWiseOrderCounter(config('tlecommercecore.order_delivery_status.ready_to_ship')) }}
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
                                {{ $order_repository->statusWiseOrderCounter(config('tlecommercecore.order_delivery_status.shipped')) }}
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
                                {{ $order_repository->statusWiseOrderCounter(config('tlecommercecore.order_delivery_status.delivered')) }}
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
                                {{ $order_repository->statusWiseOrderCounter(config('tlecommercecore.order_delivery_status.cancelled')) }}
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
                                        {{ $order_repository->statusWiseOrderCounter(config('tlecommercecore.order_delivery_status.pending')) }}
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
                                        {{ $order_repository->statusWiseOrderCounter(config('tlecommercecore.order_delivery_status.processing')) }}
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

                                    <strong class="ml-2">{{ $order_repository->statusWiseOrderCounter(config('tlecommercecore.order_delivery_status.ready_to_ship')) }}</strong>

                                </span>
                            </td>
                            <td class="py-1 px-2">
                                <span class="d-flex align-items-center justify-content-between w-100">

                                    <span class="d-flex align-items-center" style="gap: 6px;">
                                        <span style="width: 10px; height: 10px; border-radius: 50%; background: #4e73df; display:inline-block;"></span>
                                        {{ translate('Shipped') }}:
                                    </span>
                                    <strong>{{ $order_repository->statusWiseOrderCounter(config('tlecommercecore.order_delivery_status.shipped')) }}</strong>
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
                                    <strong>{{ $order_repository->statusWiseOrderCounter(config('tlecommercecore.order_delivery_status.delivered')) }}</strong>
                                </span>
                            </td>
                            <td class="py-1 px-2">
                                <span class="d-flex align-items-center justify-content-between w-100">
                                    <span class="d-flex align-items-center" style="gap: 6px;">
                                        <span style="width: 10px; height: 10px; border-radius: 50%; background: #e74a3b; display:inline-block;"></span>
                                        {{ translate('Cancelled') }}:
                                    </span>
                                    <strong>{{ $order_repository->statusWiseOrderCounter(config('tlecommercecore.order_delivery_status.cancelled')) }}</strong>
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
                                        <a
                                            href="{{ route('plugin.tlcommercecore.orders.details', ['id' => $order->id]) }}">{{ $order->order_code }}</a>
                                    </td>
                                    <td>{{ $order->created_at->format('d M Y h:i A') }}</td>
                                    <td>
                                        @if ($order->customer_info != null)
                                            <a
                                                href="{{ route('plugin.tlcommercecore.customers.details', ['id' => $order->customer_id]) }}">{{ $order->customer_info->name }}</a>
                                        @else
                                            <a href="#">{{ $order->guest_customer->name }}
                                                <span class="badge" style="background-color: #ff5A1f; color: #fff;">
                                                    {{ translate('Guest') }}
                                                </span>
                                                <!-- <span class="badge badge-info ml-1">Guest</span></a> -->
                                        @endif
                                    </td>
                                    <td class="text-center">{!! currencyExchange($order->total_payable_amount) !!}</td>
                                    <td class="text-center">
                                        @switch($order->delivery_status)
                                            @case(config('tlecommercecore.order_delivery_status.pending'))
                                                <span class="badge badge-secondary">{{ translate('Pending') }}</span>
                                                @break
                                            @case(config('tlecommercecore.order_delivery_status.processing'))
                                                <span class="badge badge-warning">{{ translate('Processing') }}</span>
                                                @break
                                            @case(config('tlecommercecore.order_delivery_status.ready_to_ship'))
                                                <span class="badge badge-info">{{ translate('Ready to Ship') }}</span>
                                                @break
                                            @case(config('tlecommercecore.order_delivery_status.shipped'))
                                                <span class="badge badge-primary">{{ translate('Shipped') }}</span>
                                                @break
                                            @case(config('tlecommercecore.order_delivery_status.delivered'))
                                                <span class="badge badge-success">{{ translate('Delivered') }}</span>
                                                @break
                                            @case(config('tlecommercecore.order_delivery_status.cancelled'))
                                                <span class="badge badge-danger">{{ translate('Cancelled') }}</span>
                                                @break
                                            @default
                                                <span class="badge badge-secondary">{{ translate('Unknown') }}</span>
                                        @endswitch
                                    </td>
                                    <!-- <td class="text-center">
                                        @switch($order->payment_status)
                                            @case(config('tlecommercecore.order_payment_status.unpaid'))
                                                <span class="badge badge-danger">{{ translate('Unpaid') }}</span>
                                                @break
                                            @case(config('tlecommercecore.order_payment_status.paid'))
                                                <span class="badge badge-success">{{ translate('Paid') }}</span>
                                                @break
                                            @default
                                                <span class="badge badge-secondary">{{ translate('Unknown') }}</span>
                                        @endswitch
                                    </td> -->
                                    <td class="text-center">
                                        <a href="{{ route('plugin.tlcommercecore.orders.details', ['id' => $order->id]) }}"
                                            class="details-btn">
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
                                        <img 
                                                src="{{ $customer->image 
                                                    ? str_replace('/public', '', getFilePath($customer->image))
                                                    : asset('backend/assets/img/avatar/avatar-user.png') 
                                                }}"
                                                alt="Customer Image"
                                            />
                                        <!-- <img src="{{ asset(getFilePath($customer->image, true)) }}"
                                            alt="{{ $customer->name }}"> -->
                                    </div>
                                    <div class="content">
                                        <a href="{{ route('plugin.tlcommercecore.customers.details', ['id' => $customer->id]) }}"
                                            class="black mb-1">{{ $customer->name }}
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

                                            <img 
                                                src="{{ $product->thumbnail_image
                                                    ? str_replace('/public', '', getFilePath($product->thumbnail_image))
                                                    : asset('backend/assets/img/avatar/avatar-user.png') 
                                                }}"
                                                alt="Customer Image"
                                            />
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
                                         <img src="{{ str_replace('/public', '',  asset(getFilePath($category['icon'], true)) )
                                        }}"
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
                                         <img src="{{ str_replace('/public', '', asset(getFilePath($brand['logo'])))
                                          }}"
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
                data: { type: chart_data_type },
                url: '{{ route("plugin.tlcommercecore.reports.sales.chart") }}',
                success: function(data) {
                    if (data.success) {
                        categories = data.times;
                        sales_chart.updateSeries([{ name: 'Sales', data: data.sales }]);
                        sales_chart.updateOptions({ xaxis: { categories: data.times } });
                    }
                }
            });
        }

        var sales_chart_options = {
            series: [],
            chart: {
                height: 340,
                type: 'line',
                toolbar: { show: true },
                zoom: { enabled: false }
            },
            dataLabels: { enabled: false },
            stroke: { curve: 'smooth', width: 3, dashArray: 0 },
            colors: ['#FFBA5A', '#8381FD'],
            grid: { borderColor: '#f5f5f5' },
            markers: {
                size: 7,
                colors: ["#ff5A1f"],
                hover: { size: 8 }
            },
            xaxis: { categories: [] },
            yaxis: {
                tickAmount: 4,
                labels: {
                    formatter: function(val) { return val.toFixed(2); }
                }
            },
            responsive: [{
                breakpoint: 576,
                options: {
                    markers: { size: 5, colors: ["#ff5A1f"], hover: { size: 5 } }
                }
            }]
        };

        var sales_chart = new ApexCharts(document.querySelector("#apex_sales_report_chart"), sales_chart_options);
        sales_chart.render();

        // Doughnut chart using ApexCharts
        var donut_options = {
            series: [
                {{ $order_repository->statusWiseOrderCounter(config('tlecommercecore.order_delivery_status.pending')) }},
                {{ $order_repository->statusWiseOrderCounter(config('tlecommercecore.order_delivery_status.processing')) }},
                {{ $order_repository->statusWiseOrderCounter(config('tlecommercecore.order_delivery_status.ready_to_ship')) }},
                {{ $order_repository->statusWiseOrderCounter(config('tlecommercecore.order_delivery_status.shipped')) }},
                {{ $order_repository->statusWiseOrderCounter(config('tlecommercecore.order_delivery_status.delivered')) }},
                {{ $order_repository->statusWiseOrderCounter(config('tlecommercecore.order_delivery_status.cancelled')) }}
            ],
            chart: {
                type: 'donut',
                height: 220,
            },
            labels: ['Pending', 'Approved', 'Ready to Ship', 'Shipped', 'Delivered', 'Cancelled'],
            colors: ['#f6c23e', '#36b9cc', '#ff8c00', '#4e73df', '#1cc88a', '#e74a3b'],
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
            legend: { show: false },
            dataLabels: { enabled: false },
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
    border-color: #ff5A1f !important; /* In case there is a border */
}

/* Optional: If you want a slight hover effect on the inactive buttons */
.list-button li.chart-switcher:hover:not(.active) {
    background-color: #ff5A1f !important;
    color: #ffffff !important;
    cursor: pointer;
}

</style>
