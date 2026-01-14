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
        ->select('order_code', 'id', 'created_at', 'total_payable_amount', 'customer_id')
        ->orderBy('id', 'DESC')
        ->take(5)
        ->get();
    
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

        .apexcharts-toolbar {
            top: -30px !important;
        }

        .img-20 {
            width: 20px !important;
            height: 20px !important;
        }

        /* added by Neo */

        
     .card.glassmorphic-card {
  background: rgba(136, 132, 132, 0.25) !important;
  backdrop-filter: blur(8px);
  -webkit-backdrop-filter: blur(8px);
    border-radius: 20px;
    border: 1px solid rgba(255, 255, 255, 0.3);

     box-shadow: 
    0 8px 32px rgba(0, 0, 0, 0.1),
    inset 0 1px 0 rgba(255, 255, 255, 0.5),
    inset 0 -1px 0 rgba(255, 255, 255, 0.1),
    inset 0 0 24px 12px rgba(255, 255, 255, 1.2);

    position: relative;
    overflow: visible;
    transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1),
                box-shadow 0.4s ease,
                background 0.4s ease;
}

        .card.glassmorphic-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(
                90deg,
                transparent,
                rgba(255, 255, 255, 0.8),
                transparent
            );
            z-index: 2;
        }

        .card.glassmorphic-card .state::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 1px;
            height: 100%;
            background: linear-gradient(
                180deg,
                rgba(255, 255, 255, 0.6),
                transparent,
                rgba(255, 255, 255, 0.2)
            );
            z-index: 2;
        }
        .card.glassmorphic-card::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(
                90deg,
                #ff6b35 0%,
                #ff8c42 25%,
                #ffa726 50%,
                #ff8c42 75%,
                #ff6b35 100%
            );
            box-shadow: 
                0 -4px 24px rgba(255, 107, 53, 0.6),
                0 -2px 12px rgba(255, 140, 66, 0.5),
                inset 0 1px 2px rgba(255, 255, 255, 0.4);
            z-index: 3;
            transition: all 0.4s ease;
        }

        .card.glassmorphic-card:hover::after {
            height: 6px;
            box-shadow: 
                0 -6px 32px rgba(255, 107, 53, 0.8),
                0 -3px 16px rgba(255, 140, 66, 0.6),
                inset 0 1px 2px rgba(255, 255, 255, 0.5);
        }

        .card.glassmorphic-card .state::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(
                180deg,
                transparent 0%,
                transparent 70%,
                rgba(255, 107, 53, 0.02) 90%,
                rgba(255, 140, 66, 0.04) 100%
            );
            pointer-events: none;
            z-index: 0;
        }

        .card.glassmorphic-card:hover {
            transform: scale(1.05) translateY(8px);
            /* scale: 1.05; */
            box-shadow: 
                0 20px 60px rgba(0, 0, 0, 0.25),
                inset 0 1px 0 rgba(255, 255, 255, 0.5),
                inset 0 -1px 0 rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 140, 66, 0.3);
            background: rgba(255, 255, 255, 0.12) !important;
        }

        .card.glassmorphic-card .state {
            position: relative;
            z-index: 2;
        }

        .card.glassmorphic-card .state-content {
            position: relative;
            z-index: 2;
        }

        .card.glassmorphic-card .state-content h2 {
            color: #ffffff;
            font-weight: 700;
         
            letter-spacing: -0.5px;
        }

        .card.glassmorphic-card .state-content p {
            color: rgba(255, 255, 255, 0.95);
            font-weight: 500;
            letter-spacing: 0.3px;
        }

        @keyframes premium-shimmer {
            0% {
                background-position: -200% center;
            }
            100% {
                background-position: 200% center;
            }
        }

        .card.glassmorphic-card:hover::after {
            background: linear-gradient(
                90deg,
                #ff6b35 0%,
                #ff8c42 20%,
                #ffb74d 40%,
                #ff8c42 60%,
                #ff6b35 80%,
                #ff8c42 100%
            );
            background-size: 200% 100%;
            animation: premium-shimmer 3s ease-in-out infinite;
        }ound: linear-gradient(
                90deg,
                #ff6b35 0%,
                #ff8c42 20%,
                #ffb74d 40%,
                #ff8c42 60%,
                #ff6b35 80%,
                #ff8c42 100%
            );
            background-size: 200% 100%;
            animation: premium-shimmer 3s ease-in-out infinite;
        }

        .col-xl-3,
.col-sm-6,
.mb-30,
.card,
.state
.row {
    overflow: visible !important;
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
    <div class="col-xl-3 col-sm-6">
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
    </div>
   
    <!--End total customers-->
    <!--Total Orders-->
    <div class="col-xl-3 col-sm-6">
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
    </div>
    <!--End total Orders-->
    <!--Total Products-->
    <div class="col-xl-3 col-sm-6">
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
    </div>
    <!--End Products-->
    <!--Total Sales-->
    <div class="col-xl-3 col-sm-6">
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
    </div>
    <!--End total Sales-->

    <!--Sales Reports-->
    <div class="col-xl-8 col-12">
        <div class="card mb-30">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start align-items-sm-center media">
                    <div
                        class="d-flex justify-content-start justify-content-sm-between align-items-start align-items-sm-center flex-column flex-sm-row mb-sm-n3 media-body">
                        <div class="title-content mb-4 mr-sm-5 mb-sm-0">
                            <h4 class="">{{ translate('Sale Reports') }}</h4>
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
    <div class="col-xl-4 col-lg-5 grid-item">
        <!-- Card -->
        <div class="card mb-30">
            <div class="card-body p-30">
                <!-- Transaction History -->
                <div class="trans-history">
                    <!-- Transaction History Item -->
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
                    <!-- End Transaction History Item -->
                    <!-- Transaction History Item -->
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
                    <!-- End Transaction History Item -->
                    <!-- Transaction History Item -->
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
                    <!-- End Transaction History Item -->
                    <!-- Transaction History Item -->
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
                    <!-- End Transaction History Item -->
                    <!-- Transaction History Item -->
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
                    <!-- End Transaction History Item -->
                    <!-- Transaction History Item -->
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
                    <!-- End Transaction History Item -->
                </div>
                <!-- End Transaction History -->
            </div>
        </div>
        <!-- End Card -->
    </div>
    <!--End order counter-->
    <!--Recent Orders-->
    <div class="col-xl-8 col-lg-7 col-12 mb-20">
        <!-- Card -->
        <div class="card">
            <div class="card-body pb-0">
                <div class="d-flex justify-content-between">
                    <div class="title-content">
                        <h4 class="mb-2">{{ translate('Recent Orders') }}</h4>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="style--three table-centered text-nowrap">
                    <thead>
                        <tr>
                            <th>{{ translate('Order ID') }}</th>
                            <th>{{ translate('Date') }}</th>
                            <th>{{ translate('Customer') }}</th>
                            <th>{{ translate('Total Amount') }}</th>
                            <th>{{ translate('Action') }}</th>
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
                                            <a href="#">{{ $order->guest_customer->name }}<span
                                                    class="badge badge-info ml-1">Guest</span></a>
                                        @endif
                                    </td>
                                    <td>{!! currencyExchange($order->total_payable_amount) !!}</td>
                                    <td>
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
    <div class="col-xl-4 col-lg-6 mb-20">
        <div class="card mb-30">
            <div class="card-body">
                <div class="d-flex align-items-start align-items-sm-end justify-content-between mb-3">
                    <div class="">
                        <h4 class="mb-1">{{ translate('Top Customers') }}</h4>
                    </div>
                </div>
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
        <div class="card mb-30">
            <div class="card-body">
                <div class="d-flex align-items-start align-items-sm-end justify-content-between mb-3">
                    <div class="">
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
    <div class="col-xl-4 col-lg-6">
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
                                        <!-- <img src="{{ asset(getFilePath($category['icon'], true)) }}"
                                            alt="{{ $category['name'] }}"> -->
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
    </div>
    <!--End Top Categories-->
    <!--Top Brands-->
    <div class="col-xl-4 col-lg-6">
        <!-- Card -->
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
                                        <!-- <img src="{{ asset(getFilePath($brand['logo'])) }}"
                                            alt="{{ $brand['name'] }}" class="img-20"> -->
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
        <!-- End Card -->
    </div>
    <!--End Top Brands-->
</div>

@push('script')
    <script>
        (function($) {
            "use strict";
            let chart_data_type = "monthly";
            let categories = [];
            //change chart data type
            $(".chart-switcher").on('click', function(e) {
                e.preventDefault();
                $('.chart-switcher').removeClass('active');
                $(this).addClass('active');
                chart_data_type = $(this).data('type');
                getChartData();
            });

            //Get data from api
            function getChartData() {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    },
                    type: "POST",
                    data: {
                        type: chart_data_type
                    },
                    url: '{{  route("plugin.tlcommercecore.reports.sales.chart") }}',                                        success: function(data) {
                        if (data.success) {
                            categories = data.times;
                            sales_chart.updateSeries([{
                                name: 'Sales',
                                data: data.sales
                            }])

                            sales_chart.updateOptions({
                                xaxis: {
                                    categories: data.times
                                }
                            })
                        }
                    }
                });
            }
            
            //chart options
            var sales_chart_options = {
                series: [],
                chart: {
                    height: 340,
                    type: 'line',
                    toolbar: {
                        show: true,
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
                    dashArray: 3
                },
                colors: ['#FFBA5A', '#8381FD'],
                grid: {
                    borderColor: '#f5f5f5',
                },
                markers: {
                    size: 7,
                    colors: ["#67CF94"],
                    hover: {
                        size: 8,
                    }
                },
                xaxis: {
                    categories: [],
                },
                yaxis: {
                    tickAmount: 4,
                    labels: {
                        formatter: function(val, index) {
                            return val.toFixed(2);
                        }
                    }
                },
                responsive: [{
                    breakpoint: 576,
                    options: {
                        markers: {
                            size: 5,
                            colors: ["#67CF94"],
                            hover: {
                                size: 5,
                            }
                        },
                    }
                }],
            };
            //Render chart
            var sales_chart = new ApexCharts(document.querySelector(
                "#apex_sales_report_chart"), sales_chart_options);
            sales_chart.render();

            $(document).ready(function() {
                getChartData();
            });
        })(jQuery);
    </script>
@endpush
