@extends('core::base.layouts.master')
@section('title')
    {{ translate('Transaction history') }}
@endsection
@section('custom_css')
    <link rel="stylesheet" href="{{ asset('backend/assets/plugins/daterangepicker/daterangepicker.css') }}">
    <style>
        .info {
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 100px;
        }
    </style>
@endsection
@section('main_content')
    <div class="row">

        <div class="col-12">
            <div class="card bg-transparent mb-20">

                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="" style="font-size: 30px;">{{ translate('Transaction History') }}</h4>
                            <div class="d-flex flex-wrap">
                                <!-- <a href="{{ route('plugin.tlcommercecore.shipping.locations.states.new.add') }}"
                                    class="btn long btn-orange">{{ translate('Add New State') }}</a> -->
                                <button class="btn long btn-orange" data-toggle="modal"
                                    data-target="#order-shipping-label-modal">{{ translate('Print Transaction History') }}</button>
                                </div>
                    </div>
                </div>

            </div>
        </div>


        <div class="col-12">
            <div class="card mb-2" style="border-radius: 12px !important; overflow: hidden !important;">
                <!-- <div class="card-body border-bottom2 mb-20">
                    <div class="d-sm-flex justify-content-between align-items-center">
                        <h4 class="font-20">{{ translate('Transaction history') }}</h4>
                    </div>
                </div> -->

                <!-- <div class="invoice-details-header bg-white d-flex align-items-sm-center flex-column flex-sm-row mb-30 justify-content-sm-between">
                
                    <div class="d-flex align-items-center">
                        <h4 class="font-20">{{ translate('Transaction history') }}</h4>
                    </div>

                    <div class="d-flex flex-wrap gap-10 invoice-header-right justify-content-around mt-3 mt-sm-0">
                        <div class="shipping-lebel-btn">
                            <button class="btn btn-success long mb-1 mt-2 mt-sm-0" data-toggle="modal"
                                data-target="#order-shipping-label-modal">{{ translate('Print Transaction History') }}</button>
                        </div>
                   
                    </div>

                </div> -->
                <div class="px-2 filter-area" style="margin-top: 35px;">
                    <form method="get" action="{{ route('plugin.tlcommercecore.payments.transactions.history') }}">

                        <div class="row">

                            <div class="col-md-3 mb-3">

                                <select class="theme-input-style w-100" name="payment_method">
                                    <option value="">{{ translate('Payment Method') }}</option>
                                    @foreach ($payment_methods as $method)
                                        <option value="{{ $method['name'] }}" @selected(request()->has('payment_method') && request()->get('payment_method') == $method['name'])>
                                            {{ $method['name'] }}
                                        </option>
                                    @endforeach
                                </select>

                            </div>

                            <div class="col-md-3 mb-3">

                                <input type="text" name="search" class="theme-input-style w-100"
                                    value="{{ request()->has('search') ? request()->get('search') : '' }}"
                                    placeholder="Customer name">

                            </div>

                            <div class="col-md-3 mb-3">

                                <input type="text" class="theme-input-style w-100" id="transactionDateRange"
                                placeholder="Filter by date" name="transaction_date" readonly>

                            </div>

                        </div>
                        
                        <div class="d-flex justify-content-end align-items-end" style="margin-right: 20px; gap: 5px;">

                        

                            <a class="btn long btn-orange" style="background: white !important; color: black !important; border: 1px solid black !important; border-radius: 6px !important; box-shadow: none !important;"
                                href="{{ route('plugin.tlcommercecore.payments.transactions.history') }}">{{ translate('Clear') }}</a>

                            
                            <button type="submit" class="btn long btn-orange">{{ translate('Filter') }}</button>

                        </div>
                        
                        

                    </form>
                    
                </div>
                <div class="table-responsive">
                    <table class="hoverable">
                        <thead style="background: #F3F4F6;">
                            <tr>
                                <th>
                                    #
                                </th>
                                <th class="text-center">{{ translate('Date') }}</th>
                                <th class="text-center">{{ translate('Payment Method') }}</th>
                                <th>{{ translate('Payment For') }}</th>
                                <th class="text-center">{{ translate('Customer') }}</th>
                                <th class="text-center">{{ translate('Amount') }}</th>
                                <th class="text-center">{{ translate('Info') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($transactions->count() > 0)
                                @foreach ($transactions as $key => $transaction)
                                    <tr>
                                        <td>
                                            {{ $key + 1 }}
                                        </td>
                                        <td class="text-center">{{ $transaction->created_at }}</td>
                                        <td class="text-center">{{ $transaction->payment_method }}</td>
                                        <td>{{ $transaction->payment_for }}</td>
                                        <td class="text-center">
                                            @if ($transaction->customer_info != null)
                                                <a
                                                    href="{{ route('plugin.tlcommercecore.customers.details', ['id' => $transaction->customer_info->id]) }}">{{ $transaction->customer_info->name }}</a>
                                            @else
                                                @if ($transaction->guest_customer_info != null)
                                                    <p class="text-capitalize">
                                                        {{ $transaction->guest_customer_info->name }}<span
                                                            class="ml-1 badge badge-info">Guest</span>
                                                    </p>
                                                @endif
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            {!! currencyExchange($transaction->paid_amount) !!}
                                        </td>
                                        <td>
                                            <p class="info cursor-pointer" data-info="{{ $transaction->payment_info }}">
                                                {{ $transaction->payment_info }}</p>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="7">
                                        <p class="alert alert-danger text-center">{{ translate('Nothing found') }}</p>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                    <div class="pgination px-3">
                        {!! $transactions->withQueryString()->onEachSide(1)->links('pagination::bootstrap-5-custom') !!}
                    </div>
                </div>
            </div>

        </div>
    </div>
    <!--view details modal-->
    <div id="details-modal" class="details-modal modal fade show" aria-modal="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title h6">{{ translate('Payment Details') }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="content"></div>
                </div>
            </div>
        </div>
    </div>
    <!--End view details modal-->


    <!--Shipping label modal-->
        <div id="order-shipping-label-modal" class="modal fade" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-md modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title h6 bold">{{ translate('Export Transaction History') }}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="export-transaction-form" method="POST" action="{{ route('plugin.tlcommercecore.payment.print.transaction.history') }}">
                            @csrf
                            
                            <div class="form-group mb-20">
                                <label class="black bold mb-2">{{ translate('Select Date Range') }}</label>
                                <input type="text" class="theme-input-style" id="exportDateRange" name="export_date_range" placeholder="Select dates" readonly>
                            </div>

                            <div class="form-group mb-20">
                                <label class="black bold mb-2">{{ translate('Payment Method') }} ({{ translate('Optional') }})</label>
                                <select class="theme-input-style" name="payment_method">
                                    <option value="">{{ translate('All Methods') }}</option>
                                    @foreach ($payment_methods as $method)
                                        <option value="{{ $method['name'] }}">{{ $method['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-row mt-30">
                                <div class="col-12 d-flex justify-content-end gap-10">
                                    <button type="button" class="btn long btn-danger" data-dismiss="modal">{{ translate('Cancel') }}</button>
                                    <button type="submit" class="btn long btn-success">{{ translate('Download Excel') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
            <!-- <div id="order-shipping-label-modal" class="order-shipping-label-modal modal fade show" aria-modal="true"
                role="dialog">
                <div class="modal-dialog modal-md modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title h6 bold">{{ translate('Payment Transaction History') }}</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body pt-0">
                            <form method="POST"
                                action="{{ route('plugin.tlcommercecore.orders.print.shipping.label') }}">
                                @csrf
                                <p class="text-error"></p>
                                
                                
                                <div class="form-row">
                                    <div class="col-12 d-flex justify-content-between">
                                        <input type="submit" name="action" value="preview"
                                            class="btn long btn-info rounded"></input>
                                        <input type="submit" name="action" value="download"
                                            class="btn long btn-success rounded"></input>

                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div> -->
            <!--End Transaction History modal-->

@endsection
@section('custom_scripts')
    <script src="{{ asset('backend/assets/plugins/moment/moment.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('backend/assets/plugins/daterangepicker/daterangepicker.js') }}">
    </script>
    <script>
        (function($) {
            "use strict";
            // Filter date range
            function cb(start, end) {

                let initVal = '{{ request()->has('transaction_date') ? request()->get('transaction_date') : '' }}';
                $('#transactionDateRange').val(initVal);
            }

            var start = moment().subtract(0, 'days');
            var end = moment();

            $('#transactionDateRange').on('apply.daterangepicker', function(ev, picker) {
                let val = picker.startDate.format('YYYY-MM-DD') + ' to ' + picker.endDate.format(
                    'YYYY-MM-DD')
                $('#transactionDateRange').val(val);
            });
            $('#transactionDateRange').daterangepicker({
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
             * View payment info
             * 
             */
            // $(".info").on('click', function(e) {
            //     let data = $(this).data('info');
            //     $("#content").html('<p>' + data + '</p>');
            //     $("#details-modal").modal('show');
            // });
            $(".info").on('click', function(e) {
                let data = $(this).data('info');
                
                // Check if data is an object; if so, turn it into a string
                let displayContent = typeof data === 'object' ? JSON.stringify(data, null, 2) : data;
                
                $("#content").html('<pre>' + displayContent + '</pre>'); // Using <pre> preserves formatting
                $("#details-modal").modal('show');
            });

            $('#exportDateRange').daterangepicker({
                showCustomRangeLabel: true,
                ranges: {
                    'Today': [moment(), moment()],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                }
            });

            // Optional: Clear the input when modal opens to force selection
            $('#order-shipping-label-modal').on('show.bs.modal', function () {
                $('#exportDateRange').val('');
            });

            
        })(jQuery);
    </script>
@endsection


<style>

    button.btn-orange,
    a.btn-orange {
        background: #ff5A1f !important;
        border-color: #e64a10 !important;
        color: #fff !important;
        transition: background 0.2s ease;
        border-radius: 6px !important;
        box-shadow: none !important;

    }

    button.btn-orange:hover,
    a.btn-orange:hover {
        background: #ff7545 !important;
        border-color: #e07b00 !important;
        color: #fff !important;
        box-shadow: none !important;

    }

    button.btn-orange:focus,
    button.btn-orange:active,
    button.btn-orange:active:focus {
        background: #ff7545 !important;
        border-color: #e07b00 !important;
        box-shadow: none !important;
        outline: none !important;
    }

    /* 1. The background of the switch track when ON */
    .switch.medium input:checked ~ .control {
        background-color: #ff5A1f !important;
        border-color: #ff8c00 !important;
    }

    /* 2. The sliding circle (the knob) */
    /* We usually keep this white or a very light grey for contrast */
    .switch.medium .control:after {
        background-color: #ffffff !important;
        border: 1px solid #e0e0e0;
        box-shadow: none !important;

    }

    /* 3. If your template uses a shadow on the circle when active */
    .switch.medium input:checked ~ .control:after {
        border-color: #ff8c00 !important; 
        box-shadow: none !important;

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

    select.theme-input-style {
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23666' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 12px bottom 10px; /* adjust 12px to move arrow left/right */
        padding-right: 2rem;
    }

    /* 4. Optional: Style the Focus state (when clicked) to remove the blue shadow */
    /* .pagination .page-item .page-link:focus {
        box-shadow: 0 0 0 0.2rem rgba(255, 90, 31, 0.25);
    } */

</style>