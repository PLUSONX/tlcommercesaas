@extends('core::base.layouts.master')
@section('title')
    {{ translate('Inhouse Orders') }}
@endsection
@section('custom_css')
    <link rel="stylesheet" href="{{ asset('backend/assets/plugins/daterangepicker/daterangepicker.css') }}">
    <!-- <link rel="stylesheet" href="{{ asset('/public/backend/assets/plugins/daterangepicker/daterangepicker.css') }}"> -->
@endsection
@section('main_content')
    <div class="row">
        <div class="d-flex justify-content-between align-items-center w-100 ml-3">
            <h4 class="font-20 mb-0">{{ translate('Inhouse Orders') }}</h4>

            <button type="button"
                id="toggle-updated-filter-btn"
                class="btn btn-filter-toggle"
                style="width: 36px; height: 36px; padding: 0; display: flex; align-items: center; justify-content: center; margin-right: 10px; box-shadow: none !important;">
                <x-lucide-sliders-horizontal class="icon-size" />
            </button>

        </div>
        <div class="col-12 mt-30 mb-20">
            <div class="card p-2" style="border-radius: 12px !important; overflow: hidden !important;">

                <div class="px-2 filter-area d-flex align-items-center mb-10" style="display: none; flex-wrap: wrap; gap: 8px; margin-top: 15px;">

                    <!-- ALL: Active only if NO status is set -->
                    <a href="{{ route('plugin.tlcommercecore.orders.inhouse') }}"
                    class="btn-filter-item {{ !request()->has('delivery_status') && !request()->has('payment_status') ? 'active' : '' }}">
                        {{ translate('All') }} ({{ $order_counter['all'] ?? 0 }})
                    </a>

                    <!-- DELIVERY STATUS BUTTONS -->
                    @php
                        $delivery_types = [
                            'pending' => 'Pending',
                            'processing' => 'Processing',
                            'ready_to_ship' => 'Ready to ship',
                            'shipped' => 'Shipped',
                            'delivered' => 'Delivered',
                            'cancelled' => 'Cancelled'
                        ];
                    @endphp

                    @foreach($delivery_types as $key => $label)
                        <a href="{{ route('plugin.tlcommercecore.orders.inhouse', ['delivery_status' => config('tlecommercecore.order_delivery_status.'.$key)]) }}"
                        class="btn-filter-item {{ request('delivery_status') == config('tlecommercecore.order_delivery_status.'.$key) ? 'active' : '' }}">
                            {{ translate($label) }} ({{ $order_counter[$key] ?? 0 }})
                        </a>
                    @endforeach

                    <!-- PAYMENT STATUS BUTTONS -->
                    <a href="{{ route('plugin.tlcommercecore.orders.inhouse', ['payment_status' => config('tlecommercecore.order_payment_status.unpaid')]) }}"
                    class="btn-filter-item {{ request('payment_status') == config('tlecommercecore.order_payment_status.unpaid') ? 'active' : '' }}">
                        {{ translate('Unpaid') }} ({{ $order_counter['unpaid'] ?? 0 }})
                    </a>

                    <a href="{{ route('plugin.tlcommercecore.orders.inhouse', ['payment_status' => config('tlecommercecore.order_payment_status.paid')]) }}"
                    class="btn-filter-item {{ request('payment_status') == config('tlecommercecore.order_payment_status.paid') ? 'active' : '' }}">
                        {{ translate('Paid') }} ({{ $order_counter['paid'] ?? 0 }})
                    </a>

                </div>
               

                <!--Filter Counter-->
                 <!-- <div class="px-2 filter-area d-flex align-items-center mb-20">

                    <a href="{{ route('plugin.tlcommercecore.orders.inhouse') }}"
                        class="btn-filter-item {{ !request()->has('delivery_status') ? 'active' : '' }}">{{ translate('All') }}
                        ({{ $order_counter != null ? $order_counter['all'] : 0 }})
                    </a>

                    <a href="{{ route('plugin.tlcommercecore.orders.inhouse', ['delivery_status' => config('tlecommercecore.order_delivery_status.pending')]) }}"
                        class="btn-filter-item {{ !request()->has('delivery_status') ? 'active' : '' }}">{{ translate('Pending') }}
                        ({{ $order_counter != null ? $order_counter['pending'] : 0 }})
                    </a>

                    <a href="{{ route('plugin.tlcommercecore.orders.inhouse', ['delivery_status' => config('tlecommercecore.order_delivery_status.processing')]) }}"
                        class="btn-filter-item {{ !request()->has('delivery_status') ? 'active' : '' }}">{{ translate('Processing') }}
                        ({{ $order_counter != null ? $order_counter['processing'] : 0 }})
                    </a>

                    <a href="{{ route('plugin.tlcommercecore.orders.inhouse', ['delivery_status' => config('tlecommercecore.order_delivery_status.ready_to_ship')]) }}"
                        class="btn-filter-item {{ !request()->has('delivery_status') ? 'active' : '' }}">{{ translate('Ready to ship') }}
                        ({{ $order_counter != null ? $order_counter['ready_to_ship'] : 0 }})
                    </a>

                    <a href="{{ route('plugin.tlcommercecore.orders.inhouse', ['delivery_status' => config('tlecommercecore.order_delivery_status.shipped')]) }}"
                        class="btn-filter-item {{ !request()->has('delivery_status') ? 'active' : '' }}">{{ translate('To Shipped') }}
                        ({{ $order_counter != null ? $order_counter['shipped'] : 0 }})
                    </a>

                    <a href="{{ route('plugin.tlcommercecore.orders.inhouse', ['delivery_status' => config('tlecommercecore.order_delivery_status.delivered')]) }}"
                        class="btn-filter-item {{ !request()->has('delivery_status') ? 'active' : '' }}">{{ translate('Delivered') }}
                        ({{ $order_counter != null ? $order_counter['delivered'] : 0 }})
                    </a>

                    <a href="{{ route('plugin.tlcommercecore.orders.inhouse', ['payment_status' => config('tlecommercecore.order_payment_status.unpaid')]) }}"
                        class="btn-filter-item {{ !request()->has('delivery_status') ? 'active' : '' }}">{{ translate('Unpaid') }}
                        ({{ $order_counter != null ? $order_counter['unpaid'] : 0 }})
                    </a>

                    <a href="{{ route('plugin.tlcommercecore.orders.inhouse', ['payment_status' => config('tlecommercecore.order_payment_status.paid')]) }}"
                        class="btn-filter-item {{ !request()->has('delivery_status') ? 'active' : '' }}">{{ translate('Paid') }}
                        ({{ $order_counter != null ? $order_counter['paid'] : 0 }})
                    </a>

                    <a href="{{ route('plugin.tlcommercecore.orders.inhouse', ['delivery_status' => config('tlecommercecore.order_delivery_status.cancelled')]) }}"
                        class="btn-filter-item {{ !request()->has('delivery_status') ? 'active' : '' }}">{{ translate('Cancelled') }}
                        ({{ $order_counter != null ? $order_counter['cancelled'] : 0 }})
                    </a> -->

                    <!-- <a href="{{ route('plugin.tlcommercecore.orders.inhouse') }}"
                        class="btn-filter-item {{ !request()->has('delivery_status') ? 'active' : '' }}">{{ translate('All') }}
                        ({{ $order_counter != null ? $order_counter['all'] : 0 }})
                    </a>
                    <a href="{{ route('plugin.tlcommercecore.orders.inhouse', ['delivery_status' => config('tlecommercecore.order_delivery_status.pending')]) }}"
                        class="btn-filter-item {{ !request()->has('delivery_status') ? 'active' : '' }}">{{ translate('Pending') }}
                        ({{ $order_counter != null ? $order_counter['pending'] : 0 }})
                    </a>
                    <a href="{{ route('plugin.tlcommercecore.orders.inhouse', ['delivery_status' => config('tlecommercecore.order_delivery_status.processing')]) }}"
                        class="btn-filter-item {{ !request()->has('delivery_status') ? 'active' : '' }}">{{ translate('Processing') }}
                        ({{ $order_counter != null ? $order_counter['processing'] : 0 }})
                    </a>
                    <a href="{{ route('plugin.tlcommercecore.orders.inhouse', ['delivery_status' => config('tlecommercecore.order_delivery_status.ready_to_ship')]) }}"
                        class="btn-filter-item {{ !request()->has('delivery_status') ? 'active' : '' }}">{{ translate('Ready to ship') }}
                        ({{ $order_counter != null ? $order_counter['ready_to_ship'] : 0 }})
                    </a>
                    <a href="{{ route('plugin.tlcommercecore.orders.inhouse', ['delivery_status' => config('tlecommercecore.order_delivery_status.shipped')]) }}"
                        class="btn-filter-item {{ !request()->has('delivery_status') ? 'active' : '' }}">{{ translate('To Shipped') }}
                        ({{ $order_counter != null ? $order_counter['shipped'] : 0 }})
                    </a>
                    <a href="{{ route('plugin.tlcommercecore.orders.inhouse', ['delivery_status' => config('tlecommercecore.order_delivery_status.delivered')]) }}"
                        class="btn-filter-item {{ !request()->has('delivery_status') ? 'active' : '' }}">{{ translate('Delivered') }}
                        ({{ $order_counter != null ? $order_counter['delivered'] : 0 }})
                    </a>
                    <a href="{{ route('plugin.tlcommercecore.orders.inhouse', ['payment_status' => config('tlecommercecore.order_payment_status.unpaid')]) }}"
                        class="btn-filter-item {{ !request()->has('delivery_status') ? 'active' : '' }}">{{ translate('Unpaid') }}
                        ({{ $order_counter != null ? $order_counter['unpaid'] : 0 }})
                    </a>
                    <a href="{{ route('plugin.tlcommercecore.orders.inhouse', ['payment_status' => config('tlecommercecore.order_payment_status.paid')]) }}"
                        class="btn-filter-item {{ !request()->has('delivery_status') ? 'active' : '' }}">{{ translate('Paid') }}
                        ({{ $order_counter != null ? $order_counter['paid'] : 0 }})
                    </a>
                    <a href="{{ route('plugin.tlcommercecore.orders.inhouse', ['delivery_status' => config('tlecommercecore.order_delivery_status.cancelled')]) }}"
                        class="btn-filter-item {{ !request()->has('delivery_status') ? 'active' : '' }}">{{ translate('Cancelled') }}
                        ({{ $order_counter != null ? $order_counter['cancelled'] : 0 }})
                    </a> -->
                <!-- </div> -->
                <!--End filter Counter-->
                <!-- <div class="px-2 filter-area d-flex align-items-center"> -->
                    <!-- <select class="theme-input-style" id="bulkActionSelector">
                        <option value="">
                            {{ translate('Bulk Action') }}
                        </option>
                        <option value="d-{{ config('tlecommercecore.order_delivery_status.processing') }}">
                            {{ translate('Change status to processing') }}
                        </option>
                        <option value="d-{{ config('tlecommercecore.order_delivery_status.ready_to_ship') }}">
                            {{ translate('Change status to ready to ship') }}
                        </option>
                        <option value="d-{{ config('tlecommercecore.order_delivery_status.shipped') }}">
                            {{ translate('Change status to shipped') }}
                        </option>
                        <option value="d-{{ config('tlecommercecore.order_delivery_status.delivered') }}">
                            {{ translate('Change status to delivered') }}
                        </option>
                        <option value="d-{{ config('tlecommercecore.order_delivery_status.cancelled') }}">
                            {{ translate('Change status to cancelled') }}
                        </option>
                        <option value="p-{{ config('tlecommercecore.order_payment_status.paid') }}">
                            {{ translate('Change status to paid') }}
                        </option>
                        <option value="p-{{ config('tlecommercecore.order_payment_status.unpaid') }}">
                            {{ translate('Change status to unpaid') }}
                        </option>
                        <option value="delete_all">
                            {{ translate('Delete Order') }}
                        </option>
                    </select>
                    <button class="btn long btn-danger fire-bulk-action"
                        href="{{ route('plugin.tlcommercecore.orders.inhouse') }}" type="submit">{{ translate('Apply') }}
                    </button> -->
                    <!--End bulk actions-->
                    <!--Filter area-->
                    <!-- <form method="get" action="{{ route('plugin.tlcommercecore.orders.inhouse') }}">
                        <select class="theme-input-style mb-2" name="per_page">
                            <option value="">{{ translate('Per page') }}</option>
                            <option value="20" @selected(request()->has('per_page') && request()->get('per_page') == '20')>20</option>
                            <option value="50" @selected(request()->has('per_page') && request()->get('per_page') == '50')>50</option>
                            <option value="all" @selected(request()->has('per_page') && request()->get('per_page') == 'all')>All</option>
                        </select>
                        <select class="theme-input-style mb-2" name="delivery_status">
                            <option value="">{{ translate('Delivery status') }}</option>
                            <option value="{{ config('tlecommercecore.order_delivery_status.pending') }}"
                                @selected(request()->has('delivery_status') && request()->get('delivery_status') == config('tlecommercecore.order_delivery_status.pending'))>
                                {{ translate('Pending') }}</option>
                            <option value="{{ config('tlecommercecore.order_delivery_status.processing') }}"
                                @selected(request()->has('delivery_status') && request()->get('delivery_status') == config('tlecommercecore.order_delivery_status.processing'))>
                                {{ translate('Processing') }}</option>
                            <option value="{{ config('tlecommercecore.order_delivery_status.ready_to_ship') }}"
                                @selected(request()->has('delivery_status') && request()->get('delivery_status') == config('tlecommercecore.order_delivery_status.ready_to_ship'))>
                                {{ translate('ready_to_ship') }}</option>
                            <option value="{{ config('tlecommercecore.order_delivery_status.shipped') }}"
                                @selected(request()->has('delivery_status') && request()->get('delivery_status') == config('tlecommercecore.order_delivery_status.shipped'))>
                                {{ translate('Shipped') }}</option>
                            <option value="{{ config('tlecommercecore.order_delivery_status.delivered') }}"
                                @selected(request()->has('delivery_status') && request()->get('delivery_status') == config('tlecommercecore.order_delivery_status.delivered'))>
                                {{ translate('Delivered') }}</option>

                            <option value="{{ config('tlecommercecore.order_delivery_status.cancelled') }}"
                                @selected(request()->has('delivery_status') && request()->get('delivery_status') == config('tlecommercecore.order_delivery_status.cancelled'))>
                                {{ translate('Cancelled') }}</option>
                        </select>
                        <select class="theme-input-style mb-2" name="payment_status">
                            <option value="">{{ translate('Payment status') }}</option>
                            <option value="{{ config('tlecommercecore.order_payment_status.paid') }}"
                                @selected(request()->has('payment_status') && request()->get('payment_status') == config('tlecommercecore.order_payment_status.paid'))>{{ translate('Paid') }}
                            </option>
                            <option value="{{ config('tlecommercecore.order_payment_status.unpaid') }}"
                                @selected(request()->has('payment_status') && request()->get('payment_status') == config('tlecommercecore.order_payment_status.unpaid'))>{{ translate('Unpaid') }}
                            </option>
                        </select>
                        <input type="text" class="theme-input-style mb-2" id="orderDateRange"
                            placeholder="Filter by date" name="order_date" readonly>
                        <input type="text" name="order_code" class="theme-input-style  mb-2"
                            value="{{ request()->has('order_code') ? request()->get('order_code') : '' }}"
                            placeholder="Enter order code">
                        <button type="submit" class="btn long">{{ translate('Filter') }}</button>
                    </form>

                    @if (request()->has('order_code') ||
                            request()->has('payment_status') ||
                            request()->has('delivery_status') ||
                            request()->has('order_date'))
                        <a class="btn long btn-danger" href="{{ route('plugin.tlcommercecore.orders.inhouse') }}">
                            {{ translate('Clear Filter') }}
                        </a>
                    @endif -->
                    <!--End filter area-->



                <!-- </div> -->

                <!-- <button id="toggle-filter-btn" class="btn btn-primary">
                    Show/Hide Filters
                </button> -->

                <!-- <button type="button"
                    id="toggle-updated-filter-btn"
                    class="btn btn-filter-toggle"
                    style="width: 36px; height: 36px; padding: 0; display: flex; align-items: center; justify-content: center;">
                    <x-lucide-sliders-horizontal width="20" height="20" />
                </button> -->
            </div>
        </div>

         <div class="col-12 mb-20 filter-div" style="display: none;">
            <div class="card p-2" style="border-radius: 12px !important; overflow: hidden !important;">

                <div class="px-2 filter-area">
                    <form method="get" action="{{ route('plugin.tlcommercecore.orders.inhouse') }}">
                        <div class="row">

                            <!-- Per Page -->
                            <div class="col-md-3 mb-3">
                                <label class="font-16 bold black d-block mb-2">{{ translate('Page') }}</label>
                                <select class="theme-input-style w-100" name="per_page">
                                    <option value="">{{ translate('Select per page') }}</option>
                                    <option value="20" @selected(request()->get('per_page') == '20')>20</option>
                                    <option value="50" @selected(request()->get('per_page') == '50')>50</option>
                                    <option value="all" @selected(request()->get('per_page') == 'all')>{{ translate('All') }}</option>
                                </select>
                            </div>

                            <!-- Delivery Status -->
                            <div class="col-md-3 mb-3">
                                <label class="font-16 bold black d-block mb-2">{{ translate('Delivery Status') }}</label>
                                <select class="theme-input-style w-100" name="delivery_status">
                                    <option value="">{{ translate('All delivery statuses') }}</option>
                                    <option value="{{ config('tlecommercecore.order_delivery_status.pending') }}" @selected(request()->get('delivery_status') == config('tlecommercecore.order_delivery_status.pending'))>{{ translate('Pending') }}</option>
                                    <option value="{{ config('tlecommercecore.order_delivery_status.processing') }}" @selected(request()->get('delivery_status') == config('tlecommercecore.order_delivery_status.processing'))>{{ translate('Processing') }}</option>
                                    <option value="{{ config('tlecommercecore.order_delivery_status.ready_to_ship') }}" @selected(request()->get('delivery_status') == config('tlecommercecore.order_delivery_status.ready_to_ship'))>{{ translate('Ready to Ship') }}</option>
                                    <option value="{{ config('tlecommercecore.order_delivery_status.shipped') }}" @selected(request()->get('delivery_status') == config('tlecommercecore.order_delivery_status.shipped'))>{{ translate('Shipped') }}</option>
                                    <option value="{{ config('tlecommercecore.order_delivery_status.delivered') }}" @selected(request()->get('delivery_status') == config('tlecommercecore.order_delivery_status.delivered'))>{{ translate('Delivered') }}</option>
                                    <option value="{{ config('tlecommercecore.order_delivery_status.cancelled') }}" @selected(request()->get('delivery_status') == config('tlecommercecore.order_delivery_status.cancelled'))>{{ translate('Cancelled') }}</option>
                                </select>
                            </div>

                            <!-- Payment Status -->
                            <div class="col-md-3 mb-3">
                                <label class="font-16 bold black d-block mb-2">{{ translate('Payment Status') }}</label>
                                <select class="theme-input-style w-100" name="payment_status">
                                    <option value="">{{ translate('All payment statuses') }}</option>
                                    <option value="{{ config('tlecommercecore.order_payment_status.paid') }}" @selected(request()->get('payment_status') == config('tlecommercecore.order_payment_status.paid'))>{{ translate('Paid') }}</option>
                                    <option value="{{ config('tlecommercecore.order_payment_status.unpaid') }}" @selected(request()->get('payment_status') == config('tlecommercecore.order_payment_status.unpaid'))>{{ translate('Unpaid') }}</option>
                                </select>
                            </div>

                            <!-- Order Date -->
                            <div class="col-md-3 mb-3">
                                <label class="font-16 bold black d-block mb-2">{{ translate('Order Date') }}</label>
                                <input type="text" class="theme-input-style w-100" id="orderDateRange" placeholder="{{ translate('Filter by date') }}" name="order_date" readonly>
                            </div>

                            <!-- Order Code -->
                            <div class="col-md-3 ">
                                <label class="font-16 bold black d-block mb-2">{{ translate('Order Code') }}</label>
                                <input type="text" name="order_code" class="theme-input-style w-100" value="{{ request()->get('order_code', '') }}" placeholder="{{ translate('Enter order code') }}">
                            </div>

                            <!-- Actions -->
                            <div class="col-md-3 d-flex align-items-end" style="gap: 10px">
                                @if(request()->hasAny(['order_code', 'payment_status', 'delivery_status', 'order_date', 'per_page']))
                                    <a class="btn long btn-danger w-100" href="{{ route('plugin.tlcommercecore.orders.inhouse') }}">{{ translate('Clear') }}</a>
                                @endif
                                <button type="submit" class="btn long w-100 btn-orange" style="margin-top: 10px; box-shadow: none !important;">
                                    {{ translate('Filter') }}
                                </button>
                                <!-- <button type="submit" class="btn long w-100" style="background: #ff8c00;">
                                    {{ translate('Filter') }}
                                </button> -->
                            </div>

                        </div>
                    </form>
                </div>

            </div>
        </div>

         <div class="col-12">
            <div class="card mb-30" style="border-radius: 12px !important; overflow: hidden !important;">
                <div class="table-responsive">
                    <table id="conditionTable" class="hoverable text-nowrap" style="table-layout: fixed; width: 100%;">
                        <thead>
                            <tr>
                                <!-- <th>
                                    <label class="position-relative">
                                        <input type="checkbox" name="select_all" class="select-all">
                                        <span class="checkmark"></span>
                                    </label>
                                </th> -->
                                <th class="font-16">{{ translate('Order Code') }}</th>
                                <th class="font-16">{{ translate('Order Date') }}</th>
                                <th class="font-16">{{ translate('Customer') }}</th>
                                <th class="font-16 text-center" style="padding: 0px !important; width: 80px !important;">{{ translate('Products') }}</th>
                                <th class="font-16 text-center" style="padding: 0px !important; width: 80px !important;">{{ translate('Amount') }}</th>
                                <th class="font-16 text-center" >{{ translate('Payment') }}</th>
                                <th class="font-16 text-center">{{ translate('Order') }}</th>
                                <th class="font-16 text-center">{{ translate('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($orders->count() > 0)
                                @foreach ($orders as $key => $order)
                                    <tr>
                                        <!-- <td style="width: 10px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                            <div class="d-flex align-items-center ">
                                                <label class="position-relative m-0">
                                                    <input type="checkbox" name="items[]" class="item-id"
                                                        value="{{ $order->id }}">
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                        </td> -->
                                        <td style="width: 50px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                            <a title="View Details" class="order-link"
                                                href="{{ route('plugin.tlcommercecore.orders.details', ['id' => $order->id]) }}">
                                                {{ $order->order_code }}
                                                <!-- @if ($order->read_at == null)
                                                    <span class="badge" style="background-color: #FF5A1F; color: #fff;">{{ translate('New') }}</span>
                                                @endif -->
                                            </a>

                                        </td>

                                        <td style="width: 100px;">
                                            <div style="width: 100px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" 
                                                title="{{ $order->created_at }}">
                                                {{ date('d M, Y', strtotime($order->created_at)) }}
                                            </div>
                                        </td>

                                        <!-- <td  style="width: 100px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                            {{ $order->created_at }}</td> -->

                                        <td style="width: 50px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                            @if ($order->customer_name != null)
                                                <a
                                                    href="{{ route('plugin.tlcommercecore.customers.details', ['id' => $order->customer_id]) }}">
                                                    {{ $order->customer_name }}
                                                </a>
                                            @else
                                                {{ $order->guest_customer }}
                                                <!-- <span class="badge badge-info">{{ translate('Guest') }}</span> -->
                                                <span class="badge font-12" style="background-color: #FF5A1F; color: #fff;">{{ translate('Guest') }}</span>
                                            @endif
                                        </td>
                                        <td class="text-center" style="width: 20px;">{{ $order->total_product }}</td>
                                        <td class="text-center" style="width: 20px;">{!! currencyExchange($order->total_payable_amount) !!}</td>

                                        <td class="text-center">
                                            @if ($order->payment_status == config('tlecommercecore.order_payment_status.paid'))
                                                <span class="badge-payment badge-paid">
                                                    <i class="fa fa-check-circle mr-1"></i> {{ translate('Paid') }}
                                                </span>
                                            @endif

                                            @if ($order->payment_status == config('tlecommercecore.order_payment_status.unpaid'))
                                                <span class="badge-payment badge-unpaid">
                                                    <i class="fa fa-exclamation-circle mr-1"></i> {{ translate('Unpaid') }}
                                                </span>
                                            @endif
                                        </td>

                                        <td class="text-center">
                                            @if ($order->delivery_status == config('tlecommercecore.order_delivery_status.pending'))
                                                <span class="badge-payment badge-pending">
                                                    <i class="fa fa-check-circle mr-1"></i> {{ translate('Pending') }}
                                                </span>
                                            @elseif ($order->delivery_status == config('tlecommercecore.order_delivery_status.processing'))
                                                <span class="badge-payment badge-processing">
                                                    <i class="fa fa-exclamation-circle mr-1"></i> {{ translate('Processing') }}
                                                </span>
                                            @elseif ($order->delivery_status == config('tlecommercecore.order_delivery_status.ready_to_ship'))
                                                <span class="badge-payment badge-ready">
                                                    <i class="fa fa-exclamation-circle mr-1"></i> {{ translate('Ready To Ship') }}
                                                </span>
                                            @elseif ($order->delivery_status == config('tlecommercecore.order_delivery_status.shipped'))
                                                <span class="badge-payment badge-shipped">
                                                    <i class="fa fa-exclamation-circle mr-1"></i> {{ translate('Shipped') }}
                                                </span>
                                            @elseif ($order->delivery_status == config('tlecommercecore.order_delivery_status.delivered'))
                                                <span class="badge-payment badge-delivered">
                                                    <i class="fa fa-exclamation-circle mr-1"></i> {{ translate('Delivered') }}
                                                </span>
                                            @elseif ($order->delivery_status == config('tlecommercecore.order_delivery_status.cancelled'))
                                                <span class="badge-payment badge-cancelled">
                                                    <i class="fa fa-exclamation-circle mr-1"></i> {{ translate('Cancelled') }}
                                                </span>
                                            @endif
                                        </td>
                                        

                                        <td>
                                            @if ($order->delivery_status == config('tlecommercecore.order_delivery_status.pending'))
                                                <button class="btn-success order-accept-btn w-50" style="border-radius: 6px; height: 30px;"
                                                    data-order="{{ $order->id }}" title="Accept order">
                                                    <i style="font-size: 18px;" class="icofont-check-circled"></i>
                                                </button>
                                            @endif
                                            @if ($order->delivery_status == config('tlecommercecore.order_delivery_status.pending'))
                                                <button class="btn-danger order-cancel-btn w-50" style="border-radius: 6px; height: 30px;
                                                    data-order="{{ $order->id }}" title="Cancel order">
                                                    <i style="font-size: 18px;"
                                                        class="icofont-delete"></i>
                                                </button>
                                            @endif
                                            @if ($order->delivery_status == config('tlecommercecore.order_delivery_status.processing') 
                                                ||
                                                $order->delivery_status == config('tlecommercecore.order_delivery_status.delivered')
                                                ||
                                                $order->delivery_status == config('tlecommercecore.order_delivery_status.cancelled')    
                                            )
                                                <div class="text-center">

                                                <button class="btn-orange status-details-btn w-100" title="Update order status"
                                                    data-order="{{ $order->id }}"
                                                    style="border-radius: 6px; height: 30px;">
                                                    <i class="icofont-ui-edit"></i>
                                                </button>

                                                <!-- <button class="btn-info status-details-btn w-75" title="Update order status"
                                                    data-order="{{ $order->id }}">
                                                    <i class="icofont-ui-edit"></i>
                                                </button> -->

                                                </div>
                                                
                                            @endif

                                            @php
                                                $shippingCourierOrder = \Plugin\TlcommerceCore\Models\ShippingCourierOrders::where('order_id', $order->id)->first();
                                            @endphp

                                            @if ($order->delivery_status == config('tlecommercecore.order_delivery_status.ready_to_ship')
                                                ||
                                                $order->delivery_status == config('tlecommercecore.order_delivery_status.shipped')
                                                )

                                            <button class="btn-orange status-details-btn w-50" title="Update order status"
                                                    data-order="{{ $order->id }}"
                                                    style="border-radius: 6px; height: 30px;">
                                                    <x-lucide-pencil width="20" height="30" />
                                            </button>

                                            @if ($shippingCourierOrder == null)

                                            <button class="btn-orange shipping-modal-open-button w-50" title="Ship Order"
                                                    data-order="{{ $order->id }}"
                                                    style="border-radius: 6px; height: 30px;">
                                                    <x-lucide-motorbike width="20" height="30" />
                                            </button>

                                            @endif

                                            @if ($shippingCourierOrder != null)

                                            <button class="btn-orange track-order-button w-50" title="Track Order"
                                                    data-order="{{ $order->id }}"
                                                    style="border-radius: 6px; height: 30px;">
                                                    <x-lucide-locate width="20" height="30" />
                                            </button>

                                            @endif

                                            @endif
                                        </td>

                                        <!-- <td>
                                            <div class="dropdown-button">
                                                <a href="#" class="d-flex align-items-center justify-content-center"
                                                    data-toggle="dropdown">
                                                    <div class="menu-icon mr-0">
                                                        <span></span>
                                                        <span></span>
                                                        <span></span>
                                                    </div>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a
                                                        href="{{ route('plugin.tlcommercecore.orders.details', ['id' => $order->id]) }}">{{ translate('Details') }}</a>
                                                </div>
                                            </div>
                                        </td> -->
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="8">
                                        <p class="alert alert-danger text-center">{{ translate('Nothing found') }}</p>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                    <div class="pgination px-3">
                        {!! $orders->withQueryString()->onEachSide(1)->links('pagination::bootstrap-5-custom') !!}
                    </div>
                </div>
            </div>

        </div>
    </div>
    <!--Status Details Modal-->
    <div id="status-details-modal" class="status-details-modal modal fade show" aria-modal="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title h6 font-weight-bold">{{ translate('Update order status') }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body pt-0">
                    <div class="order-details-content"></div>
                </div>
            </div>
        </div>
    </div>
    <!--End Status Details Modal-->
    <!--Cancel  Modal-->
    <div id="order-cancel-modal" class="order-cancel-modal modal fade show" aria-modal="true">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title h6">{{ translate('Cancel Confirmation') }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <p class="mt-1">{{ translate('Are you sure to cancel  this order') }}?</p>
                    <form method="POST" action="{{ route('plugin.tlcommercecore.orders.cancel') }}">
                        @csrf
                        <input type="hidden" name="order_id" id="cancelOrderId">
                        <button type="submit" class="btn long mt-2">{{ translate('Confirm') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--Delete Cancel Modal-->
    <!--Order Accept  Modal-->
    <div id="order-accept-modal" class="order-accept-modal modal fade show" aria-modal="true">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title h6">{{ translate('Accept Confirmation') }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <p class="mt-1">{{ translate('Are you sure you want to accept  this order') }}?</p>
                    <form method="POST" action="{{ route('plugin.tlcommercecore.orders.accept') }}">
                        @csrf
                        <input type="hidden" name="order_id" id="acceptOrderId">
                        <button type="submit" class="btn long mt-2 btn-orange">{{ translate('Confirm') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--Order Accept Modal-->

    <!--Order Ship modal-->
    <div id="order-shipping-modal" class="order-shipping-modal modal fade show" aria-modal="true"
        role="dialog">
        <div class="modal-dialog modal-md modal-dialog-centered" style="border-radius: 12px !important; overflow: hidden !important;">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title h6 bold">{{ translate('Ship Order') }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pt-0">

                    <form id="order-shipping-form">

                        <div class="form-row mt-10">

                            <input type="hidden" name="order_id" id="modal_order_id" value="">

                            <label class="font-14 bold black">{{ translate('Select Available Couriers') }}<span
                                        class="text text-danger">*</span></label>

                            <select class="theme-input-style" name="available_couriers" id="available_couriers">

                            </select>

                        </div>

                        <div class="form-row mt-20">
                            <div class="col-12 text-right">
                                <button class="btn long btn-orange submit-courier-request rounded">{{ translate('Submit Request') }}</button>
                            </div>
                        </div>
                
                    </form>

                </div>
            </div>
        </div>
    </div>


    <!--Track Order modal-->
    <div id="track-order-modal" class="track-order-modal modal fade show" aria-modal="true"
        role="dialog">
        <div class="modal-dialog modal-md modal-dialog-centered" style="border-radius: 12px !important; overflow: hidden !important;">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title h6 bold">{{ translate('Track Order') }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pt-0">

                    <form id="tracking-form">

                        <div class="form-row mt-10">

                            <div id="order-status-display" class="w-100">
                            </div>

                        </div>

                
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection
@section('custom_scripts')
    <script src="{{ asset('backend/assets/plugins/moment/moment.min.js') }}"></script>
    <!-- <script src="{{ asset('/public/backend/assets/plugins/moment/moment.min.js') }}"></script> -->
    <script type="text/javascript" src="{{ asset('backend/assets/plugins/daterangepicker/daterangepicker.js') }}">
    // <script type="text/javascript" src="{{ asset('/public/backend/assets/plugins/daterangepicker/daterangepicker.js') }}">
    </script>
    <script>
        (function($) {
            "use strict";
            /**
             * Cancel order
             *
             **/
            $('.order-cancel-btn').on('click', function(e) {
                e.preventDefault();
                let $this = $(this);
                let id = $this.data('order');
                $("#cancelOrderId").val(id);
                $("#order-cancel-modal").modal('show');
            });
            /**
             * Accept order
             *
             **/
            $('.order-accept-btn').on('click', function(e) {
                e.preventDefault();
                let $this = $(this);
                let id = $this.data('order');
                // console.log("this: ", $this);
                // console.log("id: ", id);
                $("#acceptOrderId").val(id);
                $("#order-accept-modal").modal('show');
            });
            /**
             *
             * Select all Items for bulk action
             **/
            $('.select-all').on('change', function(e) {
                if ($('.select-all').is(":checked")) {
                    $(".item-id").prop("checked", true);
                } else {
                    $(".item-id").prop("checked", false);
                }
            });
            /**
             * Bulk actions
             *
             **/
            $('.fire-bulk-action').on('click', function(e) {
                let action = $("#bulkActionSelector").val();
                if (action != "") {
                    let selected_items = [];
                    $('input[name^="items"]:checked').each(function() {
                        selected_items.push($(this).val());
                    });
                    let data = {
                        'action': action,
                        'selected_items': selected_items
                    }
                    if (selected_items.length > 0) {

                        if (action == 'delete_all') {
                            if (confirm("{{ translate('Are you sure you want to delete these items?') }}")) {
                                $.post('{{ route('plugin.tlcommercecore.orders.bulk.action') }}', {
                                    _token: '{{ csrf_token() }}',
                                    data: data
                                }, function(data) {
                                    location.reload();
                                })
                            }
                        } else {
                            $.post('{{ route('plugin.tlcommercecore.orders.bulk.action') }}', {
                                _token: '{{ csrf_token() }}',
                                data: data
                            }, function(data) {
                                location.reload();
                            });
                        }


                    } else {
                        toastr.error('{{ translate('No Item Selected') }}', "Error!");
                    }
                } else {
                    toastr.error('{{ translate('No Action Selected') }}', "Error!");
                }

            });

            /**
             * Load update order status modal
             **/
            $('.status-details-btn').on('click', function(e) {
                $(".payment-status-error").html('');
                $(".delivery-status-error").html('');
                $(".item-id").prop("checked", false);
                $(".select-all").prop("checked", false);
                e.preventDefault();
                let id = $(this).data('order');
                $.post('{{ route('plugin.tlcommercecore.orders.status.details') }}', {
                    _token: '{{ csrf_token() }}',
                    id: id
                }, function(data) {
                    $('.order-details-content').html(data);
                    changeCurrencyFont();
                    $('#status-details-modal').modal('show');
                })
            });


            $(document).ready(function() {
                $('.btn-filter-toggle').on('click', function() {
                    // 1. Toggle the visibility of the div
                    $('.filter-div').slideToggle('fast');
                    
                    // 2. Toggle the visual 'active' state of the button
                    $(this).toggleClass('is-active');
                });
            });

            /**
             * Open shipping modal
             **/
            $('.track-order-button').on('click', function(e) {
                e.preventDefault();
                // let orderId = $(this).data('order');
                let orderId = $(this).data('id');
                let displayContainer = $('#order-status-display');
                $.ajax({
                    url: '{{ route("plugin.carrier.order.updates") }}',
                    type: 'GET',
                    data: { 
                        order_id: orderId 
                    },
                    success: function(response) {
                        if (response.order_details) {
                            // console.log("order_details: ", response.order_details);
                            
                            let details = response.order_details;

                            // Format the status for better readability (e.g., picked_up -> Picked Up)
                            let statusLabel = details.status.replace(/_/g, ' ').toUpperCase();

                            let html = `
                                <div class="list-group list-group-flush">
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        <span class="bold">${'{{ translate("Current Status") }}'}:</span>
                                        <span class="badge p-2" style="background: #ff5A1f; color: #fff !important;">${statusLabel}</span>
                                    </div>
                                    <div class="list-group-item">
                                        <span class="bold d-block">${'{{ translate("Driver Information") }}'}:</span>
                                        <p class="mb-0 text-muted">
                                            ${details.driver_name ? details.driver_name : '{{ translate("Not assigned yet") }}'}<br>
                                            ${details.driver_phone ? details.driver_phone : ''}
                                        </p>
                                    </div>
                                    <div class="list-group-item small text-muted">
                                        Carrier ID: #${details.shipping_courier_id}
                                    </div>
                                </div>
                            `;
                            
                            displayContainer.html(html);

                        } else {
                            displayContainer.html('<div class="alert alert-warning">No tracking updates available for this order.</div>');
                        }
                    },
                    error: function() {
                        displayContainer.html('<div class="alert alert-danger">Error fetching data.</div>');
                        toastr.error('Failed to fetch shipping courier tracking details.');
                    }
                });

                $("#track-order-modal").modal('show');

            });
            

            /**
             * Open shipping modal
             **/
            $('.shipping-modal-open-button').on('click', function(e) {
                e.preventDefault();
                
                let orderId = $(this).data('id');

                // console.log("orderId: ", orderId);

                $('#modal_order_id').val(orderId);

                $('#available_couriers').html('<option value="">Loading...</option>');

                $.ajax({
                    url: '{{ route("plugin.active.carrier.list") }}',
                    type: 'GET',
                    success: function(response) {
                        if (response.couriers) {
                            // console.log("couriers: ", response.couriers);
                            
                            // 2. Prepare the initial placeholder
                            let options = '<option value="">Select a Courier</option>';

                            // 3. Loop through the array to build the list
                            response.couriers.forEach(function(courier) {
                                options += `<option value="${courier.id}">${courier.name}</option>`;
                            });

                            // 4. Update the specific select tag by its ID
                            $('#available_couriers').html(options);

                        } else {
                            // shippingForm.html('<p class="text-danger">No active carriers found</p>');
                        }
                    },
                    error: function() {
                        // courierSelect.html('<option value="">Error loading carriers</option>');
                        toastr.error('Failed to fetch shipping carriers.'); // If you use Toastr
                    }
                });

                $("#order-shipping-modal").modal('show');

            });

            /**
             * Will Submit Courier Request
             *
             **/
            $('.submit-courier-request').on('click', function(e) {
                // 1. Prevent the page from refreshing
                e.preventDefault();

                // 2. Reference the form and the button (for UI feedback)
                let form = $('#order-shipping-form');
                let submitBtn = $(this);
                
                // 3. Gather form data (includes order_id and available_couriers)
                let formData = form.serialize();

                // 4. Visual feedback: Disable button to prevent double-clicks
                submitBtn.prop('disabled', true).text('Submitting...');

                $.ajax({
                    type: "POST",
                    url: "{{ route('plugin.carrier.shipping.submit.courier.request') }}",
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    },
                    success: function(response) {
                        // Handle success (e.g., show a toastr notification or close modal)
                        if (response.success) {

                            toastr.success('{{ translate('Courier Request has been successfully placed!') }}');
                            $("#order-shipping-modal").modal('hide');
                            location.reload();
                        } else {
                            toastr.error(response.message || 'Something went wrong');
                        }
                    },
                    error: function(xhr) {
                        // Handle validation errors or server crashes
                        console.error(xhr.responseText);
                        toastr.error('Failed to submit request. Please try again.');
                    },
                    complete: function() {
                        // Re-enable button
                        submitBtn.prop('disabled', false).text('Submit Request');
                    }
                });
            });


            // Filter date range
            function cb(start, end) {

                let initVal = '{{ request()->has('order_date') ? request()->get('order_date') : '' }}';
                $('#orderDateRange').val(initVal);
            }

            var start = moment().subtract(0, 'days');
            var end = moment();

            $('#orderDateRange').on('apply.daterangepicker', function(ev, picker) {
                let val = picker.startDate.format('YYYY-MM-DD') + ' to ' + picker.endDate.format(
                    'YYYY-MM-DD')
                $('#orderDateRange').val(val);
            });
            $('#orderDateRange').daterangepicker({
                startDate: start,
                endDate: end,
                showCustomRangeLabel: true,
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,
                        'month').endOf('month')]
                }
            }, cb);

            cb(start, end);
        })(jQuery);
    </script>
@endsection


<style>

button.btn-orange,
a.btn-orange {
    background: #FF5A1F !important;
    /* border: 1px: !important; */
    color: #fff !important;
    transition: background 0.2s ease;
    box-shadow: none !important;
}

button.btn-orange:hover,
a.btn-orange:hover {
    background: #fff !important;
    border: 1px solid #FF5A1F !important;
    color: #FF5A1F !important;
}

button.btn-orange:focus,
button.btn-orange:active,
button.btn-orange:active:focus {
    background: #FF5A1F !important;
    box-shadow: none !important;
    outline: none !important;
    color: #fff !important;
}

.btn-filter-toggle {
    color: #FF5A1F !important; /* Changed to orange for better visibility on white backgrounds */
    background: transparent !important;
    border: 1px solid #FF5A1F !important;
    transition: all 0.2s ease-in-out;
    box-shadow: none !important;
    outline: none !important;
}

/* Combined Hover, Active, and Toggle-Active states */
.btn-filter-toggle:hover,
.btn-filter-toggle:active,
.btn-filter-toggle.is-active {
    color: #fff !important;
    background-color: #FF5A1F !important;
    border-color: #FF5A1F !important;
    box-shadow: none !important; /* Keeps the look flat and clean */
}

/* Ensures the hover state remains consistent when the button is active */
.btn-filter-toggle.is-active:hover {
    background-color: #e04a16 !important; /* A slightly darker shade for feedback when hovering a toggled button */
    border-color: #e04a16 !important;
}

.icon-size {
    margin-top: 3px;
    width: 24px !important; /* Adjust this to your liking */
    height: 24px !important;
}

.badge-payment {
    padding: 5px 12px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 500;
    display: inline-block;
}

/* Unpaid: Using your brand color #FF5A1F */
.badge-unpaid {
    background-color: rgba(255, 90, 31, 0.1); /* Light orange tint */
    color: #FF5A1F;
    border: 1px solid #FF5A1F;
}

/* Paid: Using a standard success green for clarity */
.badge-paid {
    background-color: rgba(40, 167, 69, 0.1); /* Light green tint */
    color: #28a745;
    border: 1px solid #28a745;
}


/* Pending: Neutral Grey/Blue (Waiting to start) */
.badge-pending {
    background-color: rgba(108, 117, 125, 0.1);
    color: #6c757d;
    border: 1px solid #6c757d;
}

/* Processing: Your Theme Color #FF5A1F (Active work) */
.badge-processing {
    background-color: rgba(255, 90, 31, 0.1);
    color: #FF5A1F;
    border: 1px solid #FF5A1F;
}

/* Ready to Ship: Purple (Transitioning) */
.badge-ready {
    background-color: rgba(111, 66, 193, 0.1);
    color: #6f42c1;
    border: 1px solid #6f42c1;
}

/* Shipped: Information Blue (In transit) */
.badge-shipped {
    background-color: rgba(0, 123, 255, 0.1);
    color: #007bff;
    border: 1px solid #007bff;
}

/* Delivered: Success Green (Completed) */
.badge-delivered {
    background-color: rgba(40, 167, 69, 0.1);
    color: #28a745;
    border: 1px solid #28a745;
}

/* Cancelled: Danger Red (Stopped) */
.badge-cancelled {
    background-color: rgba(220, 53, 69, 0.1);
    color: #dc3545;
    border: 1px solid #dc3545;
}

/* Base style for the filter links */
.filter-area .btn-filter-item {
    background: transparent !important; /* Always transparent initially */
    color: #FF5A1F !important;
    border: 1px solid #FF5A1F !important;
    border-radius: 20px;
    padding: 4px 15px;
    font-size: 13px;
    font-weight: 500;
    margin-right: 8px;
    transition: all 0.2s ease-in-out;
    text-decoration: none;
}

.order-link:hover {
    color: #FF5A1F !important;
}

/* Hover: Subtle hint of orange on the border and text */
.filter-area .btn-filter-item:hover {
    border-color: #fff !important;
    color: #fff !important;
    background: #FF5A1F !important; /* Keep transparent on hover */
}

/* Active state: Solid orange background */
.filter-area .btn-filter-item.active {
    background-color: #FF5A1F !important;
    border-color: #FF5A1F;
    color: #fff !important;
}

  /* 1. Change the Active Page background and border */
     .pagination .page-item.active .page-link {
        background-color: #ff5A1f !important;
        border-color: #ff5A1f !important;
        color: #ffffff !important; /* Ensure text is white on orange */
    }

    /* 2. Change the Hover state for non-active links */
    .pagination .page-item .page-link:hover {
        background-color: #ff7545 !important; /* The lighter orange we picked earlier */
        border-color: #ff7545 !important;
        color: #ffffff !important;
    }

    /* 3. Change the default text color for non-active links */
   .pagination .page-item .page-link {
        color: #ff5A1f; /* Orange text on white background */
        border-color: #dee2e6; /* Standard light border */
    }

</style>