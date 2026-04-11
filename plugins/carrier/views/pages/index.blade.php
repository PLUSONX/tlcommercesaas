@extends('core::base.layouts.master')
@section('title')
    {{ translate('Shipping Carriers') }}
@endsection
@section('main_content')
    <!-- <div class="align-items-center border-bottom2 d-flex flex-wrap gap-10 justify-content-between mb-4 pb-3">
        <h4><i class="icofont-vehicle-delivery-van"></i> {{ translate('Shipping & Delivery') }}</h4>
    </div> -->
    <div class="row">
        <div class="col-12">
            <div class="card bg-transparent mb-20">

                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="" style="font-size: 30px;">{{ translate('Shipping Carriers') }}</h4>
                            <div class="d-flex flex-wrap">
                                <a href="#" class="btn long btn-orange mr-2" data-toggle="modal"
                                data-target="#new-courier-modal">{{ translate('Create new Carrier') }}</a>
                            </div>
                    </div>
                </div>

            </div>
        </div>
        <!--3rd party courier-->
        <div class="col-12" id="ShippingCarriers">
            <div class="card mb-30" style="border-radius: 12px !important; overflow: hidden !important;">
                <!-- <div class="card-header bg-white border-bottom2">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4>{{ translate('Shipping Carriers') }}</h4>
                        <div class="d-flex align-items-center gap-15">
                            <a href="#" class="btn long mr-2" data-toggle="modal"
                                data-target="#new-courier-modal">{{ translate('Create new Carrier') }}</a>
                        </div>
                    </div>
                </div> -->
                <div class="card-body pt-0">
                    <div class="table-responsive">

                        <table class="dh-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{ translate('Logo') }}</th>
                                    <th>{{ translate('Name') }}</th>
                                    <th>{{ translate('Tracking url') }}</th>
                                    <th>{{ translate('Status') }}</th>
                                    <th class="text-right">{{ translate('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($couriers) > 0)
                                    @foreach ($couriers as $key => $courier)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td><img src="{{ str_replace('public/', '', asset(getFilePath($courier['logo'])) ) }}" 
                                                            class="img-45" alt="{{ $courier['name'] }}"></td>
                                            <!-- <td><img src="{{ asset(getFilePath($courier['logo'])) }}" class="img-45"
                                                    alt="{{ $courier['name'] }}"></td> -->
                                            <td>{{ $courier['name'] }}</td>
                                            <td>{{ $courier['tracking_url'] }}</td>
                                            <td>
                                                <label class="switch glow primary medium">
                                                    <input type="checkbox" class="courier-status"
                                                        data-courier="{{ $courier['id'] }}"
                                                        @if ($courier['status'] == config('settings.general_status.active')) checked @endif>
                                                    <span class="control"></span>
                                                </label>
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
                                                        <a href="#" data-courier="{{ $courier['id'] }}"
                                                            class="edit-courier">{{ translate('Edit') }}</a>
                                                        <a href="#" data-courier="{{ $courier['id'] }}"
                                                            class="delete-courier">{{ translate('Delete') }}</a>
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
                    </div>
                </div>
            </div>
        </div>
        <!--End 3rd Party courier-->
    </div>
    <!--New Courier Modal-->
    <div id="new-courier-modal" class="new-courier-modal modal fade show" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-md modal-dialog-centered" style="border-radius: 12px !important; overflow: hidden !important;">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title h6 bold">{{ translate('Add New Shipping Courier') }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="new-courier-form">
                        @csrf
                        <div class="form-row mb-20">
                            <label class="font-14 bold black">{{ translate('Name') }} </label>
                            <input type="text" name="name"
                                placeholder="{{ translate('Type name') }}"class="theme-input-style">

                        </div>
                        <div class="form-row mb-20">
                            <label class="font-14 bold black">{{ translate('Tracking url') }} </label>
                            <input type="text" name="tracking_url" placeholder="{{ translate('Type url') }}"
                                class="theme-input-style">

                        </div>
                        <div class="form-row mb-20">
                            <label class="font-14 bold black col-12">{{ translate('Logo') }} </label>
                            @include('core::base.includes.media.media_input', [
                                'input' => 'logo',
                                'data' => old('logo'),
                            ])

                        </div>
                        <div class="form-row">
                            <div class="col-12 text-right">
                                <button type="submit" class="btn long store-courier-btn btn-orange">{{ translate('Save') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--End New Courier Modal-->
    <!--Edit Courier Modal-->
    <div id="edit-courier-modal" class="edit-courier-modal modal fade show" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title h6 bold">{{ translate('Shipping Courier Information') }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body edit-courier-data">

                </div>
            </div>
        </div>
    </div>
    <!--End Edit Courier Modal-->

    <!--Delete Courier Modal-->
    <div id="delete-courier-modal" class="delete-courier-modal modal fade show" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title h6">{{ translate('Delete Confirmation') }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <p class="mt-1">{{ translate('Are you sure to delete this') }}?</p>
                    <form method="POST" action="{{ route('plugin.carrier.shipping.courier.delete') }}">
                        @csrf
                        <input type="hidden" id="delete-courier-id" name="id">
                        <button type="button" class="btn long btn-danger mt-2"
                            data-dismiss="modal">{{ translate('Cancel') }}</button>
                        <button type="submit" class="btn long mt-2">{{ translate('Delete') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--End Courier Modal-->
    @include('core::base.media.partial.media_modal')
@endsection
@section('custom_scripts')
    <script>
        (function($) {
            "use strict";
            initDropzone()
            $(document).ready(function() {
                is_for_browse_file = true
                filtermedia()
            });
            /** 
             * Will Store new courier
             *   
             **/
            $('.store-courier-btn').on('click', function(e) {
                e.preventDefault();
                $(document).find(".invalid-input").remove();
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    },
                    type: "POST",
                    data: $('#new-courier-form').serialize(),
                    url: '{{ route('plugin.carrier.shipping.courier.store') }}',
                    success: function(response) {
                        location.reload();
                    },
                    error: function(response) {
                        $.each(response.responseJSON.errors, function(field_name, error) {
                            $(document).find('[name=' + field_name + ']').after(
                                '<div class="invalid-input">' + error + '</div>')
                        })
                    }
                });
            });
            /**
             * Change courier status
             **/
            $('.courier-status').on('click', function(e) {
                let $this = $(this);
                let id = $this.data('courier');
                $.post('{{ route('plugin.carrier.shipping.courier.status.update') }}', {
                    _token: '{{ csrf_token() }}',
                    id: id
                }, function(data) {
                    location.reload();
                })

            });
            /**
             * Edit curier
             * 
             **/
            $('.edit-courier').on('click', function(e) {
                e.preventDefault();
                let id = $(this).data('courier');
                let data = {
                    id: id,
                }
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    },
                    type: "POST",
                    data: data,
                    url: '{{ route('plugin.carrier.shipping.courier.edit') }}',
                    success: function(data) {
                        $('.edit-courier-data').html(data)
                        $('#edit-courier-modal').modal('show')
                    }
                });
            });
            /**
             * Will delete courier
             * 
             **/
            $('.delete-courier').on('click', function(e) {
                e.preventDefault();
                let id = $(this).data('courier');
                $('#delete-courier-id').val(id);
                $("#delete-courier-modal").modal('show');
            });
            /**
             * Activate courier
             * 
             **/
            $('.activate-courier').on('click', function(e) {
                e.preventDefault();
                $.post('{{ route('plugin.carrier.shipping.courier.module.status.update') }}', {
                    _token: '{{ csrf_token() }}'
                }, function(data) {
                    location.reload();
                })
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