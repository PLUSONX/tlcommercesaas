@extends('core::base.layouts.master')
@section('title')
    {{ translate('Attributes Values') }}
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
                        <h4 class="" style="font-size: 30px;">{{ translate('Attribute Values') }}</h4>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-7 mb-20">
            <div class="card" style="border-radius: 12px !important; overflow: hidden !important;">
                <div class="mb-4">
                    
                </div>
                <div class="table-responsive">
                    <table id="attributeValueTable" class="hoverable text-nowrap border-top2">
                        <thead style="background: #F3F4F6; padding: 0px !important; margin: 0px !important;">
                            <tr>
                                <th>#</th>
                                <th class="text-center">{{ translate('Name') }}</th>
                                <th>{{ translate('Status') }}</th>
                                <th class="text-center">{{ translate('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($attribute_details->attribute_values as $key => $attribute)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td class="text-center">{{ $attribute->name }}</td>
                                    <td>
                                        <label class="switch glow primary medium">
                                            <input type="checkbox" class="change-status"
                                                data-attribute="{{ $attribute->id }}" @checked($attribute->status == config('settings.general_status.active'))>
                                            <span class="control"></span>
                                        </label>
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
                                                    href="{{ route('plugin.tlcommercecore.product.attributes.values.edit', $attribute->id) }}">
                                                    {{ translate('Edit') }}
                                                </a>
                                                <a href="#" class="delete-attribute-value"
                                                    data-attribute="{{ $attribute->id }}">{{ translate('Delete') }}</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>

        </div>
        <div class="col-md-5 mb-20">
            <div class="card" style="border-radius: 12px !important; overflow: hidden !important;">
                <div class="card-body">
                    <h4 class="" style="font-size: 30px; margin-bottom: 30px;">{{ translate('New Value') }}</h4>
                    <form action="{{ route('plugin.tlcommercecore.product.attributes.values.store') }}" method="POST">
                        @csrf
                        <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold black">{{ translate('Attribute') }} </label>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" name="attribute_name" readonly class="theme-input-style"
                                    value="{{ $attribute_details->name }}">
                                <input type="hidden" name="attribute_id" value="{{ $attribute_details->id }}">
                                @if ($errors->has('attribute_id'))
                                    <div class="invalid-input">{{ $errors->first('attribute_id') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold black">{{ translate('Value') }} </label>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" name="name" class="theme-input-style " value="{{ old('name') }}"
                                    placeholder="{{ translate('Type here') }}">
                                @if ($errors->has('name'))
                                    <div class="invalid-input">{{ $errors->first('name') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-12 text-right">
                                <button type="submit" class="btn long btn-orange">{{ translate('Save') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!--Delete Modal-->
    <div id="delete-modal" class="delete-modal modal fade show" aria-modal="true">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title h6">{{ translate('Delete Confirmation') }}</h4>
                </div>
                <div class="modal-body text-center">
                    <p class="mt-1">{{ translate('Are you sure to delete this') }}?</p>
                    <form method="POST" action="{{ route('plugin.tlcommercecore.product.attributes.values.delete') }}">
                        @csrf
                        <input type="hidden" id="delete-attribute-value-id" name="id">
                        <button type="button" class="btn long mt-2 btn-danger"
                            data-dismiss="modal">{{ translate('Cancel') }}</button>
                        <button type="submit" class="btn long mt-2">{{ translate('Delete') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--Delete Modal-->
@endsection
@section('custom_scripts')
    @include('core::base.includes.data_table.script')
    <script>
        (function($) {
            "use strict";
            /**
             * Attribute values data table
             */
            $("#attributeValueTable").DataTable({
                "responsive": false,
                "scrolX": true,
                "lengthChange": false,
                "autoWidth": false,
            }).buttons().container().appendTo('#attributeValueTable_wrapper .col-md-6:eq(0)');
            /**
             * 
             * Change status 
             * 
             * */
            $(document).on('click', '.change-status', function(e) {
                e.preventDefault();
                let $this = $(this);
                let id = $this.data('attribute');
                $.post('{{ route('plugin.tlcommercecore.product.attributes.value.status.change') }}', {
                    _token: '{{ csrf_token() }}',
                    id: id
                }, function(data) {
                    location.reload();
                })
            });
            /**
             * 
             * Delete attribute value
             * 
             * */
            $(document).on('click', '.delete-attribute-value', function(e) {
                e.preventDefault();
                let $this = $(this);
                let id = $this.data('attribute');
                $("#delete-attribute-value-id").val(id);
                $('#delete-modal').modal('show');
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


    /* 1. Hide the native browser checkbox */
    .product-id, .select-all {
        position: absolute;
        opacity: 0;
        cursor: pointer;
        height: 0;
        width: 0;
    }

    /* 2. Style the custom checkmark for both TH and TD */
    .checkmark {
        display: inline-block;
        height: 18px;
        width: 18px;
        background-color: transparent;
        border: 2px solid #ff8c00; /* Orange border */
        border-radius: 3px;
        position: relative;
        cursor: pointer;
    }

    /* 3. Style when the checkbox is checked */
    input:checked ~ .checkmark {
        background-color: #ff5A1f; /* Fill with orange */
    }

    /* 4. The actual check symbol (the white "L" shape) */
    .checkmark:after {
        content: "";
        position: absolute;
        display: none;
        left: 4px;
        top: 0px;
        width: 7px;
        height: 12px;
        border: solid white;
        border-width: 0 2px 2px 0;
        transform: rotate(45deg);
    }

    /* Show the check symbol when checked */
    input:checked ~ .checkmark:after {
        display: block;
    }

    /* 1. The background of the switch track when ON */
    .switch.primary input:checked ~ .control {
        background-color: #ff5A1f !important;
        border-color: #ff8c00 !important;
         box-shadow: none !important;
    }

    /* 2. The sliding circle (the knob) */
    /* We usually keep this white or a very light grey for contrast */
    .switch.primary .control:after {
        background-color: #ffffff !important;
        border: 1px solid #e0e0e0;
         box-shadow: none !important;
    }

    /* 3. If your template uses a shadow on the circle when active */
    .switch.primary input:checked ~ .control:after {
        border-color: #ff8c00 !important; 
    }

    /* 4. The "Glow" effect for the track */
    /* .switch.glow.primary input:checked ~ .control {
        box-shadow: 0 0 10px rgba(255, 140, 0, 0.4) !important;

    } */


    /* 1. Change the Active Page background and border */
    #attributeValueTable_paginate .pagination .page-item.active .page-link {
        background-color: #ff5A1f !important;
        border-color: #ff5A1f !important;
        color: #ffffff !important; /* Ensure text is white on orange */
    }

    /* 2. Change the Hover state for non-active links */
    #attributeValueTable_paginate .pagination .page-item .page-link:hover {
        background-color: #ff7545 !important; /* The lighter orange we picked earlier */
        border-color: #ff7545 !important;
        color: #ffffff !important;
    }

    /* 3. Change the default text color for non-active links */
    #attributeValueTable_paginate .pagination .page-item .page-link {
        color: #ff5A1f; /* Orange text on white background */
        border-color: #dee2e6; /* Standard light border */
    }

    /* 4. Optional: Style the Focus state (when clicked) to remove the blue shadow */
    #attributeValueTable_paginate .pagination .page-item .page-link:focus {
        box-shadow: 0 0 0 0.2rem rgba(255, 90, 31, 0.25);
    }
</style>
