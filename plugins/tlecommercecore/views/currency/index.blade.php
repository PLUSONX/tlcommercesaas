@extends('core::base.layouts.master')
@section('title')
    {{ translate('Currencies') }}
@endsection
@section('custom_css')
    @include('core::base.includes.data_table.css')
@endsection
@section('main_content')
    <div class="row">

        <div class="col-12">
            <div class="card bg-transparent mb-20">

                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="" style="font-size: 30px;">{{ translate('Currencies') }}</h4>
                            <div class="d-flex flex-wrap">
                                <a href="{{ route('plugin.tlcommercecore.ecommerce.add.currency') }}"
                                    class="btn long btn-orange">{{ translate('Add Currency') }}</a>
                            </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Currency List-->
        <div class="col-md-12">
            <div class="card mb-2" style="border-radius: 12px !important; overflow: hidden !important;">
            <!-- <div class="card mb-30"> -->
                <!-- <div class="card-header bg-white border-bottom2">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="font-20">{{ translate('Currencies') }}</h4>
                        <div class="d-flex flex-wrap">
                            <a href="{{ route('plugin.tlcommercecore.ecommerce.add.currency') }}"
                                class="btn long">{{ translate('Add Currency') }}</a>
                        </div>
                    </div>
                </div> -->
                <div class="table-responsive" style="margin-top: 35px;">
                    <table class="hoverable text-nowrap border-top2 " id="currency_table">
                        <thead style="background: #F3F4F6;">
                            <tr>
                                <th>#</th>
                                <th>{{ translate('Currency Name') }}</th>
                                <th>{{ translate('Currency Symbol') }}</th>
                                <th>{{ translate('Currency code ') }}</th>
                                <th>{{ translate('Conversion Rate') }}</th>
                                <th>{{ translate('Status') }}</th>
                                <th>{{ translate('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $key = 1;
                            @endphp
                            @foreach ($all_currencies as $currency)
                                <tr>
                                    <td>{{ $key }}</td>
                                    <td class="text-center">{{ $currency->name }}</td>
                                    <td class="currency-font text-center">{{ $currency->symbol }}</td>
                                    <td class="text-center">{{ $currency->code }}</td>
                                    <td class="text-center">{{ $currency->conversion_rate }}</td>
                                    <td>
                                        <label class="switch medium">
                                            <input type="checkbox" class="currency_status"
                                                id="currency_status_{{ $currency->id }}" name="status"
                                                {{ $currency->status == 1 ? 'checked' : '' }}
                                                onchange="updateCurrencyStatus('{{ $currency->id }}')">
                                            <span class="control"></span>
                                        </label>
                                        
                                    </td>
                                    <td>
                                        <div class="dropdown-button">
                                            <a href="#" class="d-flex align-items-center" data-toggle="dropdown">
                                                <div class="menu-icon style--two mr-0">
                                                    <span></span>
                                                    <span></span>
                                                    <span></span>
                                                </div>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a
                                                    href="{{ route('plugin.tlcommercecore.ecommerce.edit.currency', $currency->id) }}">Edit</a>
                                                <a href="#"
                                                    onclick="deleteConfirmation('{{ $currency->id }}')">Delete</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @php
                                    $key++;
                                @endphp
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- Currency List-->

        <!--Delete Modal-->
        <div id="delete-modal" class="delete-modal modal fade show" aria-modal="true">
            <div class="modal-dialog modal-sm modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title h6">{{ translate('Delete Confirmation') }}</h4>
                    </div>
                    <div class="modal-body text-center">
                        <p class="mt-1">{{ translate('Are you sure to delete this') }}?</p>
                        <form method="POST" action="{{ route('plugin.tlcommercecore.ecommerce.currency.delete') }}">
                            @csrf
                            <input type="hidden" id="currency_id" name="id">
                            <button type="button" class="btn long mt-2 btn-danger"
                                data-dismiss="modal">{{ translate('cancel') }}</button>
                            <button type="submit" class="btn long mt-2">{{ translate('Delete') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!--Delete Modal-->
    </div>
@endsection
@section('custom_scripts')
    @include('core::base.includes.data_table.script')
    <script type="application/javascript">
       (function($) {
            "use strict";
            $("#currency_table").DataTable();
        })(jQuery);    

        /**
         * Will request to update currency status
         */
        function updateCurrencyStatus(currency_id) {
            "use strict";
            let status = 2
            if ($('#currency_status_' + currency_id).is(":checked")) {
                status = 1
            }
            $.post("{{ route('plugin.tlcommercecore.ecommerce.update.currency.status') }}", {
                    _token: '{{ csrf_token() }}',
                    id: currency_id,
                    status: status
                },
                function(data, status) {
                    if(data.success){
                        toastr.success(data.message);
                    }else{
                        toastr.error(data.message);
                        location.reload();
                    }
                    
                }).fail(function(xhr, status, error) {
                toastr.error("Unable to update currency status");
            });
        }

        /**
         * show delete confirmation modal
         */
        function deleteConfirmation(currency_id) {
            "use strict";
            $("#currency_id").val(currency_id);
            $('#delete-modal').modal('show');
        }
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