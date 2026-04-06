@extends('core::base.layouts.master')
@section('title')
    {{ translate('Refund Requests') }}
@endsection
@section('custom_css')
@endsection
@section('main_content')
    <div class="row">

         <div class="col-12">
            <div class="card bg-transparent mb-20">

                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="" style="font-size: 30px;">{{ translate('Refund Requests') }}</h4>
                    </div>
                </div>

            </div>
        </div>

        <div class="col-12">
            <div class="card mb-2" style="border-radius: 12px !important; overflow: hidden !important;">
                <!-- <div class="card-body border-bottom2 mb-20">
                    <div class="d-sm-flex justify-content-between align-items-center">
                        <h4 class="font-20">{{ translate('Refund Requests') }}</h4>
                    </div>
                </div> -->

                <div class="px-2 filter-area" style="margin-top: 35px;">
                    <form method="get" action="{{ route('plugin.refund.requests') }}">

                        <div class="row">
                            
                            <div class="col-md-3 mb-3">
    
                                <select class="theme-input-style mb-10 w-100" name="payment_status">
                                    <option value="">{{ translate('Payment Status') }}</option>
        
                                    <option value="{{ config('tlecommercecore.return_request_payment_status.pending') }}"
                                        @selected(request()->has('payment_status') && request()->get('payment_status') == config('tlecommercecore.return_request_payment_status.pending'))>
                                        {{ translate('Pending') }}
                                    </option>
                                    <option value="{{ config('tlecommercecore.return_request_payment_status.refunded') }}"
                                        @selected(request()->has('payment_status') && request()->get('payment_status') == config('tlecommercecore.return_request_payment_status.refunded'))>
                                        {{ translate('Refunded') }}
                                    </option>
        
        
                                </select>
    
                            </div>
    
                            <div class="col-md-3 mb-3">
    
                                <select class="theme-input-style mb-10 w-100" name="return_status">
                                    <option value="">{{ translate('Return Status') }}</option>
                                    <option value="{{ config('tlecommercecore.return_request_status.pending') }}"
                                        @selected(request()->has('return_status') && request()->get('return_status') == config('tlecommercecore.return_request_status.pending'))>
                                        {{ translate('Pending') }}
                                    </option>
                                    <option value="{{ config('tlecommercecore.return_request_status.processing') }}"
                                        @selected(request()->has('return_status') && request()->get('return_status') == config('tlecommercecore.return_request_status.processing'))>
                                        {{ translate('Processing') }}
                                    </option>
                                    <option value="{{ config('tlecommercecore.return_request_status.product_received') }}"
                                        @selected(request()->has('return_status') && request()->get('return_status') == config('tlecommercecore.return_request_status.product_received'))>
                                        {{ translate('Product Received') }}
                                    </option>
                                    <option value="{{ config('tlecommercecore.return_request_status.approved') }}"
                                        @selected(request()->has('return_status') && request()->get('return_status') == config('tlecommercecore.return_request_status.approved'))>
                                        {{ translate('Approved') }}
                                    </option>
                                    <option value="{{ config('tlecommercecore.return_request_status.cancelled') }}"
                                        @selected(request()->has('return_status') && request()->get('return_status') == config('tlecommercecore.return_request_status.cancelled'))>
                                        {{ translate('Cancelled') }}
                                    </option>
                                </select>
                                
                            </div>
    
                            <div class="col-md-3 mb-3">
    
                                <input type="text" name="search" class="theme-input-style mb-10 w-100"
                                    value="{{ request()->has('search') ? request()->get('search') : '' }}"
                                    placeholder="Order code">
                                
                            </div>
    
                            <div class="col-md-3 mb-3">
    
                                <a class="btn btn-danger long mb-auto w-40"
                                    style="  background: white !important; color: black !important; border: 1px solid black; border-radius: 6px !important; box-shadow: none !important;"
                                    href="{{ route('plugin.refund.requests') }}">{{ translate('Clear') }}</a>
                                
                                <button type="submit" class="btn long btn-orange w-40">{{ translate('Filter') }}</button>
                            </div>
                        </div>



                    </form>

                </div>

                <div class="table-responsive">
                    <table class="hoverable text-nowrap">
                        <thead>
                            <tr>
                                <th>
                                    #
                                </th>
                                <th>{{ translate('Refund Code') }}</th>
                                <th>{{ translate('Order Code') }}</th>
                                <th>{{ translate('Date') }}</th>
                                <th>{{ translate('Customer') }}</th>
                                <th>{{ translate('Amount') }}</th>
                                <th>{{ translate('Status') }}</th>
                                <th>{{ translate('Payment Status') }}</th>
                                <th>{{ translate('Quick Action') }}</th>
                                <th class="text-right">{{ translate('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($refund_request_list->count() > 0)
                                @foreach ($refund_request_list as $key => $request)
                                    <tr>
                                        <td>
                                            {{ $key + 1 }}
                                        </td>
                                        <td>
                                            <a href="{{ route('plugin.refund.request.details', ['id' => $request->id]) }}">
                                                {{ $request->code }}

                                            </a>
                                        </td>
                                        <td>
                                            <a
                                                href="{{ route('plugin.tlcommercecore.orders.details', ['id' => $request->order_id]) }}">
                                                {{ $request->order_code }}

                                            </a>
                                        </td>
                                        <td>{{ $request['created_at']->format('d M Y') }}</td>
                                        <td>
                                            <a
                                                href="{{ route('plugin.tlcommercecore.customers.details', ['id' => $request->customer_id]) }}">
                                                {{ $request->customer_name }}
                                            </a>
                                        </td>
                                        <td>{!! currencyExchange($request->total_amount) !!} </td>
                                        <td>
                                            @if ($request->return_status == config('tlecommercecore.return_request_status.approved'))
                                                <p class="badge badge-success">{{ translate('approved') }}</p>
                                            @elseif ($request->return_status == config('tlecommercecore.return_request_status.processing'))
                                                <p class="badge badge-primary">{{ translate('Processing') }}</p>
                                            @elseif ($request->return_status == config('tlecommercecore.return_request_status.cancelled'))
                                                <p class="badge badge-danger">{{ translate('Cancelled') }}</p>
                                            @elseif ($request->return_status == config('tlecommercecore.return_request_status.product_received'))
                                                <p class="badge badge-dark">{{ translate('Product Received') }}</p>
                                            @else
                                                <p class="badge badge-info">{{ translate('Pending') }}</p>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($request->payment_status == config('tlecommercecore.return_request_payment_status.refunded'))
                                                <p class="badge badge-success">{{ translate('refunded') }}</p>
                                            @else
                                                <p class="badge badge-danger">{{ translate('Pending') }}</p>
                                            @endif
                                        </td>
                                        <td>
                                            <button class="btn-success quick-view" data-id="{{ $request->id }}"
                                                data-action="quick-view" title="View Details"><i class="icofont-eye"></i>
                                            </button>
                                            <button class="btn-info quick-view" data-id="{{ $request->id }}"
                                                data-action="status-update" title="Update Request Status">
                                                <i class="icofont-ui-edit"></i>
                                            </button>
                                        </td>
                                        <td>
                                            <div class="dropdown-button">
                                                <a href="#" class="d-flex align-items-center justify-content-end"
                                                    data-toggle="dropdown">
                                                    <div class="menu-icon mr-0">
                                                        <span></span>
                                                        <span></span>
                                                        <span></span>
                                                    </div>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a href="{{ route('plugin.refund.request.details', ['id' => $request->id]) }}"
                                                        class="request-details">{{ translate('Details') }}
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="10">
                                        <p class="alert alert-danger text-center">{{ translate('Nothing found') }}</p>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                    <div class="pgination px-3">
                        {!! $refund_request_list->withQueryString()->onEachSide(1)->links('pagination::bootstrap-5-custom') !!}
                    </div>
                </div>
            </div>

        </div>
    </div>
    <!--Quick view modal-->
    <div id="quick-view-modal" class="quick-view-modal modal fade show" aria-modal="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title h6 bold">{{ translate('Refund Request Information') }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>

                <div class="modal-body quick-view-content">

                </div>
            </div>
        </div>
    </div>
    <!--End quick view modal-->
@endsection
@section('custom_scripts')
    <script>
        (function($) {
            "use strict";
            /**
             * Quick view
             * 
             **/
            $(".quick-view").on('click', function(e) {
                e.preventDefault();
                let id = $(this).data('id');
                let action = $(this).data('action');
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    },
                    type: "POST",
                    data: {
                        id: id,
                        action: action
                    },
                    url: '{{ route('plugin.refund.request.quick.view') }}',
                    success: function(response) {
                        $(".quick-view-content").html(response);
                        $("#quick-view-modal").modal('show');
                    },
                    error: function(response) {
                        toastr.error('{{ translate('Details not found') }}');
                    }
                });
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