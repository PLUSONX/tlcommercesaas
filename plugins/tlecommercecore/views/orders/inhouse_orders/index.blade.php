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
        <div class="d-sm-flex justify-content-between align-items-center ml-3">
            <h4 class="font-20">{{ translate('Inhouse Orders') }}</h4>
        </div>
        <div class="col-12 mt-30 mb-20">
            <div class="card p-2" style="border-radius: 12px !important; overflow: hidden !important;">
                <div class=" mb-2">

                </div>
                <!--Filter Counter-->
                <!-- <div class="px-2 filter-area d-flex align-items-center mb-20">
                    <a href="{{ route('plugin.tlcommercecore.orders.inhouse') }}"
                        class="btn sm btn-info">{{ translate('All') }}
                        ({{ $order_counter != null ? $order_counter['all'] : 0 }})
                    </a>
                    <a href="{{ route('plugin.tlcommercecore.orders.inhouse', ['delivery_status' => config('tlecommercecore.order_delivery_status.pending')]) }}"
                        class="btn sm btn-primary">{{ translate('Pending') }}
                        ({{ $order_counter != null ? $order_counter['pending'] : 0 }})
                    </a>
                    <a href="{{ route('plugin.tlcommercecore.orders.inhouse', ['delivery_status' => config('tlecommercecore.order_delivery_status.processing')]) }}"
                        class="btn sm btn-info">{{ translate('Processing') }}
                        ({{ $order_counter != null ? $order_counter['processing'] : 0 }})
                    </a>
                    <a href="{{ route('plugin.tlcommercecore.orders.inhouse', ['delivery_status' => config('tlecommercecore.order_delivery_status.ready_to_ship')]) }}"
                        class="btn sm btn-success">{{ translate('Ready to ship') }}
                        ({{ $order_counter != null ? $order_counter['ready_to_ship'] : 0 }})
                    </a>
                    <a href="{{ route('plugin.tlcommercecore.orders.inhouse', ['delivery_status' => config('tlecommercecore.order_delivery_status.shipped')]) }}"
                        class="btn sm">{{ translate('To Shipped') }}
                        ({{ $order_counter != null ? $order_counter['shipped'] : 0 }})
                    </a>
                    <a href="{{ route('plugin.tlcommercecore.orders.inhouse', ['delivery_status' => config('tlecommercecore.order_delivery_status.delivered')]) }}"
                        class="btn sm btn-success">{{ translate('Delivered') }}
                        ({{ $order_counter != null ? $order_counter['delivered'] : 0 }})
                    </a>
                    <a href="{{ route('plugin.tlcommercecore.orders.inhouse', ['payment_status' => config('tlecommercecore.order_payment_status.unpaid')]) }}"
                        class="btn sm btn-warning">{{ translate('Unpaid') }}
                        ({{ $order_counter != null ? $order_counter['unpaid'] : 0 }})
                    </a>
                    <a href="{{ route('plugin.tlcommercecore.orders.inhouse', ['payment_status' => config('tlecommercecore.order_payment_status.paid')]) }}"
                        class="btn sm btn-success">{{ translate('Paid') }}
                        ({{ $order_counter != null ? $order_counter['paid'] : 0 }})
                    </a>
                    <a href="{{ route('plugin.tlcommercecore.orders.inhouse', ['delivery_status' => config('tlecommercecore.order_delivery_status.cancelled')]) }}"
                        class="btn sm btn-danger">{{ translate('Cancelled') }}
                        ({{ $order_counter != null ? $order_counter['cancelled'] : 0 }})
                    </a>
                </div> -->
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
                                <button type="submit" class="btn long w-100 btn-orange" style="margin-top: 10px;">
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
                    <table id="conditionTable" class="hoverable text-nowrap">
                        <thead>
                            <tr>
                                <th>
                                    <label class="position-relative mr-2">
                                        <input type="checkbox" name="select_all" class="select-all">
                                        <span class="checkmark"></span>
                                    </label>
                                </th>
                                <th class="font-16">{{ translate('Order Code') }}</th>
                                <th class="font-16">{{ translate('Order Date') }}</th>
                                <th class="font-16">{{ translate('Customer') }}</th>
                                <th class="font-16">{{ translate('Num. of Products') }}</th>
                                <th class="font-16">{{ translate('Amount') }}</th>
                                <th class="font-16 text-center">{{ translate('Order Status') }}</th>
                                <th class="font-16 text-center">{{ translate('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($orders->count() > 0)
                                @foreach ($orders as $key => $order)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center ">
                                                <label class="position-relative mr-2">
                                                    <input type="checkbox" name="items[]" class="item-id"
                                                        value="{{ $order->id }}">
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                        </td>
                                        <td>
                                            <a
                                                href="{{ route('plugin.tlcommercecore.orders.details', ['id' => $order->id]) }}">
                                                {{ $order->order_code }}
                                                @if ($order->read_at == null)
                                                    <span class="badge badge-success">{{ translate('New') }}</span>
                                                @endif
                                            </a>

                                        </td>
                                        <td>{{ $order->created_at }}</td>
                                        <td>
                                            @if ($order->customer_name != null)
                                                <a
                                                    href="{{ route('plugin.tlcommercecore.customers.details', ['id' => $order->customer_id]) }}">
                                                    {{ $order->customer_name }}
                                                </a>
                                            @else
                                                {{ $order->guest_customer }}
                                                <!-- <span class="badge badge-info">{{ translate('Guest') }}</span> -->
                                                <span class="badge font-12" style="background-color: #ff8c00; color: #fff;">{{ translate('Guest') }}</span>
                                            @endif
                                        </td>
                                        <td class="text-center">{{ $order->total_product }}</td>
                                        <td class="text-center">{!! currencyExchange($order->total_payable_amount) !!}</td>

                                        <td>
                                            @if ($order->delivery_status == config('tlecommercecore.order_delivery_status.pending'))
                                                <button class="btn-success order-accept-btn w-50" style="border-radius: 6px; height: 30px;
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
                                            @if ($order->delivery_status != config('tlecommercecore.order_delivery_status.pending'))
                                                <div class="text-center">

                                                <button class="btn-info status-details-btn w-100" title="Update order status"
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
                                        </td>

                                        <td>
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
                                        </td>
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
                    <p class="mt-1">{{ translate('Are you sure to accept  this order') }}?</p>
                    <form method="POST" action="{{ route('plugin.tlcommercecore.orders.accept') }}">
                        @csrf
                        <input type="hidden" name="order_id" id="acceptOrderId">
                        <button type="submit" class="btn long mt-2">{{ translate('Confirm') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--Order Accept Modal-->
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
    background: #ff8c00 !important;
    border-color: #ff8c00 !important;
    color: #fff !important;
    transition: background 0.2s ease;
}

button.btn-orange:hover,
a.btn-orange:hover {
    background: #e07b00 !important;
    border-color: #e07b00 !important;
    color: #fff !important;
}

button.btn-orange:focus,
button.btn-orange:active,
button.btn-orange:active:focus {
    background: #e07b00 !important;
    border-color: #e07b00 !important;
    box-shadow: none !important;
    outline: none !important;
}

</style>