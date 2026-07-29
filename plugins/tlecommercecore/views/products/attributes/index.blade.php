@extends('core::base.layouts.master')
@section('title')
    {{ translate('Attributes') }}
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
                        <h4 class="" style="font-size: 30px;">{{ translate('Attributes') }}</h4>
                        @can('Manage Add New Product')
                            <div class="d-flex flex-wrap">
                                <a href="{{ route('plugin.tlcommercecore.product.attributes.add') }}"
                                    class="btn long btn-orange">{{ translate('Add New Attribute') }}</a>
                            </div>
                        @endcan
                    </div>
                </div>

            </div>
        </div>
        <div class="col-12 mb-20">
            <div class="card" style="border-radius: 12px !important; overflow: hidden !important;">
                <div class="mb-4">
                    
                </div>
                <div class="table-responsive">
                    <table id="attributeTable" class="hoverable text-nowrap border-top2">
                        <thead style="background: #F3F4F6; padding: 0px !important; margin: 0px !important;">
                            <tr>
                                <!-- <th>
                                    <label class="position-relative mr-2">
                                        <input type="checkbox" name="select_all" class="select-all">
                                        <span class="checkmark"></span>
                                    </label>
                                </th> -->
                                <!-- <th>
                                    <div class="d-flex align-items-center">
                                        <label class="position-relative mr-2">
                                            <input type="checkbox" name="select_all" class="select-all">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                </th> -->
                                <th class="text-center">{{ translate('Name') }}</th>
                                <th>{{ translate('Values') }}</th>
                                <th>{{ translate('Status') }}</th>
                                <th>{{ translate('Multi Select') }}</th>
                                <th>{{ translate('Total Selections Required') }}</th>
                                <th class="text-center">{{ translate('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($attributes as $key => $attribute)
                                <tr>
                                    <!-- <td>
                                        <div class="d-flex align-items-center mb-3">
                                            <label class="position-relative mr-2">
                                                <input type="checkbox" name="items[]" class="item-id"
                                                    value="{{ $attribute->id }}">
                                                <span class="checkmark"></span>
                                            </label>
                                        </div>
                                    </td> -->
                                    <!-- <td>
                                        <div class="d-flex align-items-center mb-3">
                                            <label class="position-relative mr-2">
                                                <input type="checkbox" name="items[]" class="product-id" value="{{ $attribute->id }}">
                                                <span class="checkmark"></span>
                                            </label>
                                        </div>
                                    </td> -->
                                    <td class="text-center">{{ $attribute->translation('name', getLocale()) }}</td>
                                    <td>
                                        @if ($attribute->attribute_values)
                                            @foreach ($attribute->attribute_values as $value)
                                                <p class="badge badge-info"> {{ $value->name }}
                                                </p>
                                            @endforeach
                                        @endif
                                    </td>
                                    <td>
                                        <label class="switch glow primary medium">
                                            <input type="checkbox" class="change-status"
                                                data-attribute="{{ $attribute->id }}" @checked($attribute->status == config('settings.general_status.active'))>
                                            <span class="control"></span>
                                        </label>
                                    </td>
                                    <td>
                                        <label class="switch glow primary medium">
                                            <input type="checkbox" class="change-multi-select"
                                                data-attribute="{{ $attribute->id }}" @checked($attribute->multi_select)>
                                            <span class="control"></span>
                                        </label>
                                    </td>
                                    <td>{{ $attribute->multi_select_limit ?: 0 }}</td>
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
                                                    href="{{ route('plugin.tlcommercecore.product.attributes.values', $attribute->id) }}">
                                                    {{ translate('Values') }}
                                                </a>
                                                <a
                                                    href="{{ route('plugin.tlcommercecore.product.attributes.edit', ['id' => $attribute->id, 'lang' => getDefaultLang()]) }}">
                                                    {{ translate('Edit') }}
                                                </a>
                                                <a href="#" class="delete-attribute"
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
                    <form method="POST" action="{{ route('plugin.tlcommercecore.product.attributes.delete') }}">
                        @csrf
                        <input type="hidden" id="delete-attribute-id" name="id">
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
             * Attribute data table
             */
            $("#attributeTable").DataTable({
                "responsive": false,
                "scrolX": true,
                "lengthChange": false,
                "autoWidth": false,
            }).buttons().container().appendTo('#attributeTable_wrapper .col-md-6:eq(0)');
            // var bulk_actions_dropdown =
            //     '<div id="bulk-action" class="dataTables_length d-flex"><select class="theme-input-style bulk-action-selection mr-3"><option value="">{{ translate('Bulk Action') }}</option><option value="delete_all">{{ translate('Delete selection') }}</option></select><button class="btn long apply-bulk-action-btn">{{ translate('Apply') }}</button></div>';

            var bulk_actions_dropdown = `
        <div id="bulk-action" class="dataTables_length d-flex align-items-center" style="display: none;">
            <select class="theme-input-style bulk-action-selection mr-2">
                <option value="">{{ translate('Bulk Action') }}</option>
                <option value="delete_all">{{ translate('Delete selection') }}</option>
            </select>
            <button class="btn btn-orange apply-bulk-action-btn" style="background-color: #ff5A1f; color: white;">
                {{ translate('Apply') }}
            </button>
        </div>`;

            $(bulk_actions_dropdown).insertAfter("#attributeTable_wrapper #attributeTable_length");
            /**
             * 
             * Select all Items
             **/
            $('.select-all').on('change', function(e) {
                if ($('.select-all').is(":checked")) {
                    $(".item-id").prop("checked", true);
                } else {
                    $(".item-id").prop("checked", false);
                }
            });
            /**
             * 
             * Bulk action
             **/
            $('.apply-bulk-action-btn').on('click', function(e) {
                let action = $('.bulk-action-selection').val();
                if (action === 'delete_all') {
                    var selected_items = [];
                    $('input[name^="items"]:checked').each(function() {
                        selected_items.push($(this).val());
                    });

                    if (selected_items.length > 0) {
                        $.post('{{ route('plugin.tlcommercecore.product.attributes.delete.bulk') }}', {
                            _token: '{{ csrf_token() }}',
                            data: selected_items
                        }, function(data) {
                            location.reload();
                        })
                    } else {
                        toastr.error('{{ translate('No Item Selected') }}', "Error!");
                    }
                } else {
                    toastr.error('{{ translate('No Action Selected') }}', "Error!");
                }
            });
            /**
             * 
             * Change status 
             * 
             * */
            $(document).on('click', '.change-status', function(e) {
                e.preventDefault();
                let $this = $(this);
                let id = $this.data('attribute');
                $.post('{{ route('plugin.tlcommercecore.product.attributes.status.change') }}', {
                    _token: '{{ csrf_token() }}',
                    id: id
                }, function(data) {
                    location.reload();
                });

            });
            /**
             *
             * Change multi-select setting
             *
             * */
            $(document).on('click', '.change-multi-select', function(e) {
                e.preventDefault();
                let $this = $(this);
                let id = $this.data('attribute');
                $.post('{{ route('plugin.tlcommercecore.product.attributes.multi_select.change') }}', {
                    _token: '{{ csrf_token() }}',
                    id: id
                }, function(data) {
                    location.reload();
                });

            });
            /**
             * 
             * Delete attribute
             * 
             * */
            $(document).on('click', '.delete-attribute', function(e) {
                e.preventDefault();
                let $this = $(this);
                let id = $this.data('attribute');
                $("#delete-attribute-id").val(id);
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
    #attributeTable_paginate .pagination .page-item.active .page-link {
        background-color: #ff5A1f !important;
        border-color: #ff5A1f !important;
        color: #ffffff !important; /* Ensure text is white on orange */
    }

    /* 2. Change the Hover state for non-active links */
    #attributeTable_paginate .pagination .page-item .page-link:hover {
        background-color: #ff7545 !important; /* The lighter orange we picked earlier */
        border-color: #ff7545 !important;
        color: #ffffff !important;
    }

    /* 3. Change the default text color for non-active links */
    #attributeTable_paginate .pagination .page-item .page-link {
        color: #ff5A1f; /* Orange text on white background */
        border-color: #dee2e6; /* Standard light border */
    }

    /* 4. Optional: Style the Focus state (when clicked) to remove the blue shadow */
    #attributeTable_paginate .pagination .page-item .page-link:focus {
        box-shadow: 0 0 0 0.2rem rgba(255, 90, 31, 0.25);
    }
</style>
