@extends('core::base.layouts.master')
@section('title')
    {{ translate('Inhouse Orders') }}
@endsection
@section('custom_css')
    <link rel="stylesheet" href="{{ asset('backend/assets/plugins/daterangepicker/daterangepicker.css') }}">
    <!-- <link rel="stylesheet" href="{{ asset('/public/backend/assets/plugins/daterangepicker/daterangepicker.css') }}"> -->
    <style>
        .ajax-inline-spinner {
            display: inline-block;
            width: 14px;
            height: 14px;
            border: 2px solid rgba(255, 90, 31, 0.25);
            border-top-color: #FF5A1F;
            border-radius: 50%;
            animation: ajax-inline-spin 0.65s linear infinite;
            vertical-align: middle;
        }

        .ajax-inline-spinner-lg {
            width: 28px;
            height: 28px;
            border-width: 3px;
        }

        @keyframes ajax-inline-spin {
            to { transform: rotate(360deg); }
        }

        .ajax-loading-spinner {
            color: #FF5A1F;
        }

        .payment-status-spinner {
            display: inline-block;
        }

        .delivery-status-label {
            cursor: pointer;
        }

        .delivery-status-select {
            width: auto;
            min-width: 120px;
            padding: 4px 8px;
            font-size: 12px;
            display: inline-block;
        }

        .delivery-status-spinner {
            display: inline-block;
        }
    </style>
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
                        {{ translate('All') }} (<span class="inhouse-filter-count" data-count-key="all">{{ $order_counter['all'] ?? 0 }}</span>)
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
                            {{ translate($label) }} (<span class="inhouse-filter-count" data-count-key="{{ $key }}">{{ $order_counter[$key] ?? 0 }}</span>)
                        </a>
                    @endforeach

                    <!-- PAYMENT STATUS BUTTONS -->
                    <a href="{{ route('plugin.tlcommercecore.orders.inhouse', ['payment_status' => config('tlecommercecore.order_payment_status.unpaid')]) }}"
                    class="btn-filter-item {{ request('payment_status') == config('tlecommercecore.order_payment_status.unpaid') ? 'active' : '' }}">
                        {{ translate('Unpaid') }} (<span class="inhouse-filter-count" data-count-key="unpaid">{{ $order_counter['unpaid'] ?? 0 }}</span>)
                    </a>

                    <a href="{{ route('plugin.tlcommercecore.orders.inhouse', ['payment_status' => config('tlecommercecore.order_payment_status.paid')]) }}"
                    class="btn-filter-item {{ request('payment_status') == config('tlecommercecore.order_payment_status.paid') ? 'active' : '' }}">
                        {{ translate('Paid') }} (<span class="inhouse-filter-count" data-count-key="paid">{{ $order_counter['paid'] ?? 0 }}</span>)
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
            <div class="card mb-30 inhouse-orders-card" style="border-radius: 12px !important;">
                <div class="table-responsive inhouse-orders-table-wrap">
                    @php
                        $orders_meta = $orders_meta ?? ['latest_id' => 0, 'status_version' => '0', 'total' => 0];
                    @endphp
                    <table id="conditionTable"
                        class="hoverable text-nowrap inhouse-orders-table"
                        data-latest-id="{{ $orders_meta['latest_id'] ?? 0 }}"
                        data-status-version="{{ $orders_meta['status_version'] ?? '0' }}">
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
                        <tbody id="inhouse-orders-tbody">
                            @include('plugin/tlecommercecore::orders.inhouse_orders._table_rows', ['orders' => $orders])
                        </tbody>
                    </table>
                    <div class="pgination px-3" id="inhouse-orders-pagination">
                        @include('plugin/tlecommercecore::orders.inhouse_orders._pagination', ['orders' => $orders])
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

                            <div id="available-couriers-wrap">
                                <select class="theme-input-style" name="available_couriers" id="available_couriers">

                                </select>
                            </div>

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

            function setAjaxButtonLoading($btn, loading) {
                if (loading) {
                    if (!$btn.data('original-html')) {
                        $btn.data('original-html', $btn.html());
                    }
                    $btn.prop('disabled', true).html('<span class="ajax-inline-spinner"></span>');
                } else if ($btn.data('original-html')) {
                    $btn.html($btn.data('original-html')).prop('disabled', false);
                    $btn.removeData('original-html');
                }
            }

            function setAjaxContainerLoading($container, loading) {
                if (loading) {
                    $container.html('<div class="ajax-loading-spinner text-center py-3"><span class="ajax-inline-spinner ajax-inline-spinner-lg"></span></div>');
                }
            }

            function showCourierLoading() {
                $('#available_couriers').hide();
                if (!$('#courier-loading-spinner').length) {
                    $('#available-couriers-wrap').append('<div id="courier-loading-spinner" class="ajax-loading-spinner text-center py-2"><span class="ajax-inline-spinner"></span></div>');
                } else {
                    $('#courier-loading-spinner').show();
                }
            }

            function hideCourierLoading() {
                $('#courier-loading-spinner').hide();
                $('#available_couriers').show();
            }

            /**
             * Cancel order
             *
             **/
            $('.order-cancel-btn').on('click', function(e) {
                e.preventDefault();
                let $this = $(this);
                let id = $this.data('order');
                // console.log("this: ", $this);
                // console.log("id: ", id);
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
                let $btn = $(this);
                let id = $btn.data('order');
                setAjaxButtonLoading($btn, true);
                setAjaxContainerLoading($('.order-details-content'), true);
                $('#status-details-modal').modal('show');

                $.post('{{ route('plugin.tlcommercecore.orders.status.details') }}', {
                    _token: '{{ csrf_token() }}',
                    id: id
                }, function(data) {
                    $('.order-details-content').html(data);
                    changeCurrencyFont();
                }).fail(function() {
                    $('.order-details-content').html('<div class="alert alert-danger">{{ translate('Failed to load order details') }}</div>');
                    toastr.error('{{ translate('Failed to load order details') }}');
                }).always(function() {
                    setAjaxButtonLoading($btn, false);
                });
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
             * Smooth-only live tracking.
             * This uses a dedicated endpoint and does not alter Armada/Karrix tracking.
             */
            let smoothTrackingTimer = null;
            let smoothTrackingOrderId = null;

            function smoothEscape(value) {
                return $('<div>').text(value === null || value === undefined || value === '' ? '—' : String(value)).html();
            }

            function smoothSafeUrl(value) {
                if (!value) return '';
                try {
                    const parsed = new URL(value, window.location.origin);
                    return ['http:', 'https:'].includes(parsed.protocol) ? parsed.href : '';
                } catch (e) {
                    return '';
                }
            }

            function smoothFormatDate(value) {
                if (!value) return '—';
                const parsed = new Date(value);
                return isNaN(parsed.getTime()) ? smoothEscape(value) : smoothEscape(parsed.toLocaleString());
            }

            function smoothRenderTracking(details) {
                const liveLabel = details.live ? 'LIVE FROM SMOOTH' : 'CACHED UPDATE';
                const trackingUrl = smoothSafeUrl(details.tracking_url);
                const pickupUrl = smoothSafeUrl(details.pickup_tracking_url);
                const address = details.address || {};
                const items = Array.isArray(details.items) ? details.items : [];
                const events = Array.isArray(details.events) ? details.events : [];

                const addressParts = [
                    address.street_address_1,
                    address.street_address_2,
                    address.city,
                    address.state,
                    address.country,
                    address.postal_code
                ].filter(Boolean).map(smoothEscape);

                let itemHtml = '';
                if (items.length) {
                    itemHtml = items.map(function(item) {
                        const title = item.name || item.sku || 'Item';
                        return `
                            <tr>
                                <td>${smoothEscape(title)}</td>
                                <td>${smoothEscape(item.sku)}</td>
                                <td>${smoothEscape(item.quantity)}</td>
                                <td>${smoothEscape(item.unit_of_measure)}</td>
                            </tr>`;
                    }).join('');
                } else {
                    itemHtml = '<tr><td colspan="4" class="text-muted">No line details returned by Smooth.</td></tr>';
                }

                let eventHtml = '';
                if (events.length) {
                    eventHtml = events.slice().reverse().map(function(event) {
                        return `
                            <div class="d-flex justify-content-between border-bottom py-2">
                                <span>${smoothEscape(event.label || event.status)}</span>
                                <span class="text-muted font-12">${smoothFormatDate(event.created)}</span>
                            </div>`;
                    }).join('');
                } else {
                    eventHtml = `
                        <div class="d-flex justify-content-between border-bottom py-2">
                            <span>${smoothEscape(details.status_label || details.status)}</span>
                            <span class="text-muted font-12">${smoothFormatDate(details.last_local_update)}</span>
                        </div>`;
                }

                const trackingLink = trackingUrl
                    ? `<a href="${smoothEscape(trackingUrl)}" target="_blank" rel="noopener noreferrer">Open tracking</a>`
                    : '—';
                const pickupLink = pickupUrl
                    ? `<a href="${smoothEscape(pickupUrl)}" target="_blank" rel="noopener noreferrer">Open pickup tracking</a>`
                    : '—';

                return `
                    <div class="mb-3 d-flex justify-content-between align-items-center flex-wrap">
                        <div>
                            <div class="bold">Smooth Logistics</div>
                            <div class="font-12 text-muted">${smoothEscape(details.order_code)}</div>
                        </div>
                        <span class="badge p-2" style="background:#ff5A1f;color:#fff !important;">${liveLabel}</span>
                    </div>

                    ${details.message ? `<div class="alert alert-warning py-2">${smoothEscape(details.message)}</div>` : ''}

                    <div class="row">
                        <div class="col-md-6 mb-2"><div class="border rounded p-3 h-100"><span class="text-muted font-12 d-block">Smooth Status</span><span class="bold">${smoothEscape(details.status_label || details.status)}</span></div></div>
                        <div class="col-md-6 mb-2"><div class="border rounded p-3 h-100"><span class="text-muted font-12 d-block">Local Order Stage</span><span class="bold">${smoothEscape(details.local_delivery_status_label)}</span></div></div>
                        <div class="col-md-6 mb-2"><div class="border rounded p-3 h-100"><span class="text-muted font-12 d-block">Processing Status</span><span>${smoothEscape(details.processing_status)}</span></div></div>
                        <div class="col-md-6 mb-2"><div class="border rounded p-3 h-100"><span class="text-muted font-12 d-block">Tracking ID</span><span>${smoothEscape(details.tracking_id)}</span></div></div>
                    </div>

                    <div class="list-group list-group-flush border rounded mb-3">
                        <div class="list-group-item d-flex justify-content-between"><span class="bold">Tracking</span><span>${trackingLink}</span></div>
                        <div class="list-group-item d-flex justify-content-between"><span class="bold">Pickup Tracking</span><span>${pickupLink}</span></div>
                        <div class="list-group-item d-flex justify-content-between"><span class="bold">Route ID</span><span>${smoothEscape(details.route_id)}</span></div>
                        <div class="list-group-item"><span class="bold d-block">Driver</span><span>${smoothEscape(details.driver_name)}</span>${details.driver_phone ? `<br><span class="text-muted">${smoothEscape(details.driver_phone)}</span>` : ''}</div>
                        <div class="list-group-item d-flex justify-content-between"><span class="bold">Order Type / Mode</span><span>${smoothEscape(details.order_type)} / ${smoothEscape(details.delivery_mode)}</span></div>
                        <div class="list-group-item d-flex justify-content-between"><span class="bold">Dispatch Time</span><span>${smoothFormatDate(details.dispatch_time)}</span></div>
                        <div class="list-group-item d-flex justify-content-between"><span class="bold">Expected Delivery</span><span>${smoothFormatDate(details.delivery_time)}</span></div>
                        <div class="list-group-item d-flex justify-content-between"><span class="bold">Payment</span><span>${smoothEscape(details.payment_status)}${details.payment_method ? ' · ' + smoothEscape(details.payment_method) : ''}</span></div>
                        <div class="list-group-item d-flex justify-content-between"><span class="bold">Amount</span><span>${smoothEscape(details.amount)} ${smoothEscape(details.currency)}</span></div>
                        <div class="list-group-item d-flex justify-content-between"><span class="bold">Delivery Fee</span><span>${smoothEscape(details.delivery_fee)} ${smoothEscape(details.currency)}</span></div>
                        <div class="list-group-item d-flex justify-content-between"><span class="bold">Shipment Reference</span><span>${smoothEscape(details.shipment_reference)}</span></div>
                    </div>

                    <div class="border rounded p-3 mb-3">
                        <div class="bold mb-2">Delivery Address</div>
                        <div>${smoothEscape(address.name)}</div>
                        ${address.phone ? `<div class="text-muted">${smoothEscape(address.phone)}</div>` : ''}
                        ${address.email ? `<div class="text-muted">${smoothEscape(address.email)}</div>` : ''}
                        <div class="text-muted mt-1">${addressParts.length ? addressParts.join(', ') : '—'}</div>
                    </div>

                    <div class="border rounded p-3 mb-3">
                        <div class="bold mb-2">Order Items</div>
                        <div class="table-responsive">
                            <table class="table table-sm mb-0">
                                <thead><tr><th>Item</th><th>SKU</th><th>Qty</th><th>UOM</th></tr></thead>
                                <tbody>${itemHtml}</tbody>
                            </table>
                        </div>
                    </div>

                    <div class="border rounded p-3">
                        <div class="bold mb-2">Smooth Progress</div>
                        ${eventHtml}
                    </div>
                `;
            }

            function loadSmoothTracking(orderId, silent = false) {
                const displayContainer = $('#order-status-display');
                if (!silent) {
                    if (typeof setAjaxContainerLoading === 'function') {
                        setAjaxContainerLoading(displayContainer, true);
                    } else {
                        displayContainer.html('<div class="text-center py-4 text-muted">Loading Smooth live tracking...</div>');
                    }
                }

                $.ajax({
                    url: '{{ route("plugin.carrier.smooth.order.updates") }}',
                    type: 'GET',
                    data: { order_id: orderId },
                    success: function(response) {
                        if (response.order_details) {
                            displayContainer.html(smoothRenderTracking(response.order_details));
                        } else if (!silent) {
                            displayContainer.html('<div class="alert alert-warning">This order is not attached to Smooth Logistics.</div>');
                        }
                    },
                    error: function() {
                        if (!silent) {
                            displayContainer.html('<div class="alert alert-danger">Unable to fetch Smooth live tracking.</div>');
                            toastr.error('Failed to fetch Smooth live tracking details.');
                        }
                    }
                });
            }

            $(document).on('click', '.smooth-track-order-button', function(e) {
                e.preventDefault();
                smoothTrackingOrderId = $(this).data('order');
                const displayContainer = $('#order-status-display');
                $('#track-order-modal .modal-dialog').addClass('modal-lg');
                displayContainer.empty();
                $('#track-order-modal').modal('show');

                loadSmoothTracking(smoothTrackingOrderId, false);

                if (smoothTrackingTimer) {
                    clearInterval(smoothTrackingTimer);
                }
                smoothTrackingTimer = setInterval(function() {
                    if ($('#track-order-modal').hasClass('show') && smoothTrackingOrderId) {
                        loadSmoothTracking(smoothTrackingOrderId, true);
                    }
                }, 30000);
            });

            $('#track-order-modal').on('hidden.bs.modal', function() {
                if (smoothTrackingTimer) {
                    clearInterval(smoothTrackingTimer);
                    smoothTrackingTimer = null;
                }
                smoothTrackingOrderId = null;
                $('#track-order-modal .modal-dialog').removeClass('modal-lg');
            });

            /**
             * Open shipping modal
             **/
            $('.track-order-button').on('click', function(e) {
                e.preventDefault();
                // let orderId = $(this).data('order');
                let orderId = $(this).data('order');
                let displayContainer = $('#order-status-display');
                setAjaxContainerLoading(displayContainer, true);
                $("#track-order-modal").modal('show');

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

            });
            

            /**
             * Open shipping modal
             **/
            function openOrderShippingModal(orderId) {
                $('#modal_order_id').val(orderId);
                $('#available_couriers').html('');
                showCourierLoading();
                $("#order-shipping-modal").modal('show');

                $.ajax({
                    url: '{{ route("plugin.active.carrier.list") }}',
                    type: 'GET',
                    success: function(response) {
                        if (response.couriers) {
                            let options = '<option value="">Select a Courier</option>';

                            response.couriers.forEach(function(courier) {
                                options += `<option value="${courier.id}">${courier.name}</option>`;
                            });

                            $('#available_couriers').html(options);
                        } else {
                            $('#available_couriers').html('<option value="">{{ translate('No active carriers found') }}</option>');
                        }
                    },
                    error: function() {
                        $('#available_couriers').html('<option value="">{{ translate('Error loading carriers') }}</option>');
                        toastr.error('Failed to fetch shipping carriers.');
                    },
                    complete: function() {
                        hideCourierLoading();
                    }
                });
            }

            $('.shipping-modal-open-button').on('click', function(e) {
                e.preventDefault();
                openOrderShippingModal($(this).data('order'));
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
                setAjaxButtonLoading(submitBtn, true);

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
                        setAjaxButtonLoading(submitBtn, false);
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

            /**
             * Inline payment status dropdown
             **/
            var paymentStatusPaid = {{ config('tlecommercecore.order_payment_status.paid') }};
            var paymentStatusUnpaid = {{ config('tlecommercecore.order_payment_status.unpaid') }};

            function revertPaymentStatusInline($wrapper) {
                var currentStatus = String($wrapper.data('status'));
                $wrapper.find('.payment-status-spinner').hide();
                $wrapper.find('.payment-status-select').val(currentStatus).hide().prop('disabled', false);
                $wrapper.find('.payment-status-label').show();
            }

            function showPaymentStatusSpinner($wrapper) {
                $wrapper.find('.payment-status-label').hide();
                $wrapper.find('.payment-status-select').hide();
                $wrapper.find('.payment-status-spinner').show();
            }

            function updatePaymentStatusLabel($wrapper, status) {
                var $label = $wrapper.find('.payment-status-label');
                var isPaid = String(status) === String(paymentStatusPaid);

                $label.removeClass('badge-paid badge-unpaid')
                    .addClass(isPaid ? 'badge-paid' : 'badge-unpaid');

                if (isPaid) {
                    $label.html('<i class="fa fa-check-circle mr-1"></i> {{ translate('Paid') }}');
                } else {
                    $label.html('<i class="fa fa-exclamation-circle mr-1"></i> {{ translate('Unpaid') }}');
                }

                $wrapper.data('status', status);
            }

            $('#conditionTable').on('click', '.payment-status-label', function(e) {
                e.preventDefault();
                e.stopPropagation();

                var $wrapper = $(this).closest('.payment-status-inline');
                var currentStatus = String($wrapper.data('status'));

                $('.payment-status-inline').not($wrapper).each(function() {
                    revertPaymentStatusInline($(this));
                });
                $('.delivery-status-inline').each(function() {
                    revertDeliveryStatusInline($(this));
                });

                $wrapper.find('.payment-status-label').hide();
                var $select = $wrapper.find('.payment-status-select');
                $select.val(currentStatus).show().focus();
            });

            $('#conditionTable').on('change', '.payment-status-select', function() {
                var $select = $(this);
                var $wrapper = $select.closest('.payment-status-inline');
                var orderId = $wrapper.data('order-id');
                var previousStatus = String($wrapper.data('status'));
                var newStatus = String($select.val());

                if (newStatus === previousStatus) {
                    revertPaymentStatusInline($wrapper);
                    return;
                }

                $select.prop('disabled', true);
                showPaymentStatusSpinner($wrapper);

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    },
                    type: 'POST',
                    url: '{{ route('plugin.tlcommercecore.orders.payment.status.update') }}',
                    data: {
                        order_id: orderId,
                        payment_status: newStatus
                    },
                    success: function(response) {
                        if (response.success) {
                            updatePaymentStatusLabel($wrapper, newStatus);
                            revertPaymentStatusInline($wrapper);
                            toastr.success('{{ translate('Payment status updated successfully') }}');
                        } else {
                            $select.val(previousStatus);
                            revertPaymentStatusInline($wrapper);
                            toastr.error('{{ translate('Update Failed ') }}');
                        }
                    },
                    error: function() {
                        $select.val(previousStatus);
                        revertPaymentStatusInline($wrapper);
                        toastr.error('{{ translate('Update Failed ') }}');
                    },
                    complete: function() {
                        $select.prop('disabled', false);
                        $wrapper.find('.payment-status-spinner').hide();
                    }
                });
            });

            $('#conditionTable').on('blur', '.payment-status-select', function() {
                var $select = $(this);
                var $wrapper = $select.closest('.payment-status-inline');
                setTimeout(function() {
                    if ($select.is(':visible') && !$select.prop('disabled')) {
                        revertPaymentStatusInline($wrapper);
                    }
                }, 150);
            });

            /**
             * Inline delivery status dropdown
             **/
            var deliveryStatusConfig = {
                pending: {{ config('tlecommercecore.order_delivery_status.pending') }},
                processing: {{ config('tlecommercecore.order_delivery_status.processing') }},
                readyToShip: {{ config('tlecommercecore.order_delivery_status.ready_to_ship') }},
                shipped: {{ config('tlecommercecore.order_delivery_status.shipped') }},
                delivered: {{ config('tlecommercecore.order_delivery_status.delivered') }},
                cancelled: {{ config('tlecommercecore.order_delivery_status.cancelled') }}
            };

            var deliveryStatusLabels = {
                '{{ config('tlecommercecore.order_delivery_status.pending') }}': {
                    class: 'badge-pending',
                    html: '<i class="fa fa-check-circle mr-1"></i> {{ translate('Pending') }}'
                },
                '{{ config('tlecommercecore.order_delivery_status.processing') }}': {
                    class: 'badge-processing',
                    html: '<i class="fa fa-exclamation-circle mr-1"></i> {{ translate('Processing') }}'
                },
                '{{ config('tlecommercecore.order_delivery_status.ready_to_ship') }}': {
                    class: 'badge-ready',
                    html: '<i class="fa fa-exclamation-circle mr-1"></i> {{ translate('Ready To Ship') }}'
                },
                '{{ config('tlecommercecore.order_delivery_status.shipped') }}': {
                    class: 'badge-shipped',
                    html: '<i class="fa fa-exclamation-circle mr-1"></i> {{ translate('Shipped') }}'
                },
                '{{ config('tlecommercecore.order_delivery_status.delivered') }}': {
                    class: 'badge-delivered',
                    html: '<i class="fa fa-exclamation-circle mr-1"></i> {{ translate('Delivered') }}'
                },
                '{{ config('tlecommercecore.order_delivery_status.cancelled') }}': {
                    class: 'badge-cancelled',
                    html: '<i class="fa fa-exclamation-circle mr-1"></i> {{ translate('Cancelled') }}'
                }
            };

            var deliveryBadgeClasses = 'badge-pending badge-processing badge-ready badge-shipped badge-delivered badge-cancelled';

            function revertDeliveryStatusInline($wrapper) {
                var currentStatus = String($wrapper.data('status'));
                $wrapper.find('.delivery-status-spinner').hide();
                $wrapper.find('.delivery-status-select').val(currentStatus).hide().prop('disabled', false);
                $wrapper.find('.delivery-status-label').show();
            }

            function showDeliveryStatusSpinner($wrapper) {
                $wrapper.find('.delivery-status-label').hide();
                $wrapper.find('.delivery-status-select').hide();
                $wrapper.find('.delivery-status-spinner').show();
            }

            function updateDeliveryStatusLabel($wrapper, status) {
                var $label = $wrapper.find('.delivery-status-label');
                var statusKey = String(status);
                var meta = deliveryStatusLabels[statusKey] || deliveryStatusLabels[String(deliveryStatusConfig.pending)];

                $label.removeClass(deliveryBadgeClasses).addClass(meta.class);
                $label.html(meta.html);
                $wrapper.data('status', status);
            }

            $('#conditionTable').on('click', '.delivery-status-label', function(e) {
                e.preventDefault();
                e.stopPropagation();

                var $wrapper = $(this).closest('.delivery-status-inline');
                var currentStatus = String($wrapper.data('status'));

                $('.delivery-status-inline').not($wrapper).each(function() {
                    revertDeliveryStatusInline($(this));
                });
                $('.payment-status-inline').each(function() {
                    revertPaymentStatusInline($(this));
                });

                $wrapper.find('.delivery-status-label').hide();
                var $select = $wrapper.find('.delivery-status-select');
                $select.val(currentStatus).show().focus();
            });

            $('#conditionTable').on('change', '.delivery-status-select', function() {
                var $select = $(this);
                var $wrapper = $select.closest('.delivery-status-inline');
                var orderId = $wrapper.data('order-id');
                var previousStatus = String($wrapper.data('status'));
                var newStatus = String($select.val());

                if (newStatus === previousStatus) {
                    revertDeliveryStatusInline($wrapper);
                    return;
                }

                $select.prop('disabled', true);
                showDeliveryStatusSpinner($wrapper);

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    },
                    type: 'POST',
                    url: '{{ route('plugin.tlcommercecore.orders.delivery.status.update') }}',
                    data: {
                        order_id: orderId,
                        delivery_status: newStatus
                    },
                    success: function(response) {
                        if (response.success) {
                            updateDeliveryStatusLabel($wrapper, newStatus);
                            revertDeliveryStatusInline($wrapper);
                            toastr.success('{{ translate('Delivery status updated successfully') }}');

                            if (newStatus === String(deliveryStatusConfig.readyToShip)
                                && parseInt($wrapper.data('has-courier'), 10) !== 1) {
                                openOrderShippingModal(orderId);
                            }
                        } else {
                            $select.val(previousStatus);
                            revertDeliveryStatusInline($wrapper);
                            toastr.error('{{ translate('Update Failed ') }}');
                        }
                    },
                    error: function() {
                        $select.val(previousStatus);
                        revertDeliveryStatusInline($wrapper);
                        toastr.error('{{ translate('Update Failed ') }}');
                    },
                    complete: function() {
                        $select.prop('disabled', false);
                        $wrapper.find('.delivery-status-spinner').hide();
                    }
                });
            });

            $('#conditionTable').on('blur', '.delivery-status-select', function() {
                var $select = $(this);
                var $wrapper = $select.closest('.delivery-status-inline');
                setTimeout(function() {
                    if ($select.is(':visible') && !$select.prop('disabled')) {
                        revertDeliveryStatusInline($wrapper);
                    }
                }, 150);
            });

            /**
             * Auto-refresh inhouse orders table when new orders or status changes appear.
             * Note: page modals hardcode class "show", so do not use $('.modal.show') as an open check.
             **/
            var inhouseOrdersPoll = {
                latestId: parseInt($('#conditionTable').attr('data-latest-id'), 10) || 0,
                statusVersion: String($('#conditionTable').attr('data-status-version') || '0'),
                refreshing: false,
                modalOpen: false,
                intervalMs: 10000,
                metaUrl: '{{ route('plugin.tlcommercecore.orders.inhouse.latest.meta') }}',
                listUrl: '{{ route('plugin.tlcommercecore.orders.inhouse') }}'
            };

            var inhouseOrdersModalSelector = '#status-details-modal, #order-cancel-modal, #order-accept-modal, #order-shipping-modal, #track-order-modal';

            $(inhouseOrdersModalSelector).on('show.bs.modal', function() {
                inhouseOrdersPoll.modalOpen = true;
            }).on('hidden.bs.modal', function() {
                inhouseOrdersPoll.modalOpen = false;
            });

            function inhouseOrdersPollBusy() {
                if (document.hidden) {
                    return true;
                }
                // Hardcoded "modal fade show" on this page makes class-based checks unreliable.
                if (inhouseOrdersPoll.modalOpen || $('.modal-backdrop').length > 0) {
                    return true;
                }
                if ($('.payment-status-select:visible, .delivery-status-select:visible').length > 0) {
                    return true;
                }
                if ($('.payment-status-spinner:visible, .delivery-status-spinner:visible').length > 0) {
                    return true;
                }
                return false;
            }

            function updateInhouseFilterCounts(counters) {
                if (!counters) {
                    return;
                }
                $('.inhouse-filter-count').each(function() {
                    var key = $(this).data('count-key');
                    if (Object.prototype.hasOwnProperty.call(counters, key)) {
                        $(this).text(counters[key]);
                    }
                });
            }

            function refreshInhouseOrdersTable(previousLatestId) {
                if (inhouseOrdersPoll.refreshing) {
                    return;
                }
                inhouseOrdersPoll.refreshing = true;

                var params = new URLSearchParams(window.location.search);
                params.set('partial', '1');

                $.ajax({
                    type: 'GET',
                    url: inhouseOrdersPoll.listUrl + '?' + params.toString(),
                    dataType: 'json',
                    success: function(response) {
                        if (!response || !response.success) {
                            return;
                        }

                        var hadNewerOrder = parseInt(response.latest_id, 10) > previousLatestId;
                        $('#inhouse-orders-tbody').html(response.tbody);
                        $('#inhouse-orders-pagination').html(response.pagination);
                        updateInhouseFilterCounts(response.order_counter);

                        inhouseOrdersPoll.latestId = parseInt(response.latest_id, 10) || 0;
                        inhouseOrdersPoll.statusVersion = String(response.status_version || '0');
                        $('#conditionTable')
                            .attr('data-latest-id', inhouseOrdersPoll.latestId)
                            .attr('data-status-version', inhouseOrdersPoll.statusVersion);

                        if (hadNewerOrder) {
                            toastr.info('{{ translate('New order received') }}');
                            $('#inhouse-orders-tbody tr[data-order-id="' + inhouseOrdersPoll.latestId + '"]').addClass('table-warning');
                            setTimeout(function() {
                                $('#inhouse-orders-tbody tr[data-order-id="' + inhouseOrdersPoll.latestId + '"]').removeClass('table-warning');
                            }, 4000);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.warn('Inhouse orders partial refresh failed', status, error);
                    },
                    complete: function() {
                        inhouseOrdersPoll.refreshing = false;
                    }
                });
            }

            function pollInhouseOrdersMeta() {
                if (inhouseOrdersPollBusy() || inhouseOrdersPoll.refreshing) {
                    return;
                }

                $.ajax({
                    type: 'GET',
                    url: inhouseOrdersPoll.metaUrl,
                    dataType: 'json',
                    success: function(response) {
                        if (!response || !response.success) {
                            return;
                        }

                        var latestId = parseInt(response.latest_id, 10) || 0;
                        var statusVersion = String(response.status_version || '0');
                        var previousLatestId = inhouseOrdersPoll.latestId;

                        if (latestId !== inhouseOrdersPoll.latestId || statusVersion !== inhouseOrdersPoll.statusVersion) {
                            refreshInhouseOrdersTable(previousLatestId);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.warn('Inhouse orders latest-meta poll failed', status, error);
                    }
                });
            }

            pollInhouseOrdersMeta();
            setInterval(pollInhouseOrdersMeta, inhouseOrdersPoll.intervalMs);
            document.addEventListener('visibilitychange', function() {
                if (!document.hidden) {
                    pollInhouseOrdersMeta();
                }
            });
        })(jQuery);
    </script>
@endsection


<style>

.inhouse-orders-table-wrap {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

.inhouse-orders-table {
    width: 100%;
    table-layout: fixed;
}

@media (max-width: 767.98px) {
    .inhouse-orders-table-wrap {
        overflow-x: auto;
    }

    .inhouse-orders-table {
        table-layout: auto;
        min-width: 960px;
        width: max-content;
    }

    .inhouse-orders-card {
        overflow-x: auto !important;
        overflow-y: visible !important;
    }

    .inhouse-orders-table td .w-50 {
        min-width: 36px;
    }
}

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

.payment-status-label {
    cursor: pointer;
}

.payment-status-select {
    width: 90px;
    min-width: 90px;
    padding: 4px 8px;
    font-size: 12px;
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