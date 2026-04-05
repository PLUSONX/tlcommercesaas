@extends('core::base.layouts.master')
@section('title')
    {{ translate('Flash Deal') }}
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
                        <h4 class="" style="font-size: 30px;">{{ translate('Flash Deal') }}</h4>
                            <div class="d-flex flex-wrap">
                                <a href="{{ route('plugin.flashdeal.add.new') }}"
                                    class="btn long btn-orange">{{ translate('Add Flash Deal') }}</a>
                            </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="col-12">
            <div class="card mb-2" style="border-radius: 12px !important; overflow: hidden !important;">
                <!-- <div class="card-body border-bottom2 mb-20">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="font-20">{{ translate('Flash Deal') }}</h4>
                        <div class="d-flex flex-wrap">
                            <a href="{{ route('plugin.flashdeal.add.new') }}"
                                class="btn long">{{ translate('Add New Flash Deal') }}</a>
                        </div>
                    </div>
                </div> -->
                <div class="table-responsive" style="margin-top: 35px;">
                    <table id="couponTable" class="hoverable text-nowrap border-top2">
                        <thead style="background: #F3F4F6;">
                            <tr>
                                <!-- <th>
                                    <label class="position-relative mr-2">
                                        <input type="checkbox" name="select_all" class="select-all">
                                        <span class="checkmark"></span>
                                    </label>
                                </th> -->
                                <th>#</th>
                                <th>{{ translate('Title') }}</th>
                                <th>{{ translate('Start Date') }}</th>
                                <th>{{ translate('Expiry date') }}</th>
                                <th>{{ translate('Products') }}</th>
                                <th>{{ translate('Status') }}</th>
                                <th>{{ translate('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $count = 1;
                            @endphp
                            @foreach ($deals as $key => $deal)
                                <tr>
                                    <!-- <td>
                                        <div class="d-flex align-items-center mb-3">
                                            <label class="position-relative mr-2">
                                                <input type="checkbox" name="items[]" class="item-id"
                                                    value="{{ $deal->id }}">
                                                <span class="checkmark"></span>
                                            </label>
                                        </div>
                                    </td> -->
                                    <td>{{ $count }}</td>
                                    <td>{{ $deal->translation('title', getLocale()) }}</td>
                                    <td>{{ $deal->start_date }}</td>
                                    <td>{{ $deal->end_date }}</td>
                                    <td><a href="{{ route('plugin.flashdeal.products', $deal->id) }}">{{ count($deal->deal_products) }}
                                            {{ translate('Products') }}
                                    </td>
                                    <td>
                                        <label class="switch glow primary medium">
                                            <input type="checkbox" class="change-status" data-deal="{{ $deal->id }}"
                                                {{ $deal->status == '1' ? 'checked' : '' }}>
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
                                                    href="{{ route('plugin.flashdeal.edit', ['id' => $deal->id, 'lang' => getDefaultLang()]) }}">
                                                    {{ translate('Edit') }}
                                                </a>
                                                <a href="#" class="delete-deal"
                                                    data-deal="{{ $deal->id }}">{{ translate('Delete') }}</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                 @php
                                    $count++;
                                @endphp
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <!--Delete Modal-->
    <div id="delete-modal" class="delete-modal modal fade show" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title h6">{{ translate('Delete Confirmation') }}</h4>
                </div>
                <div class="modal-body text-center">
                    <p class="mt-1">{{ translate('Are you sure to delete this') }}?</p>
                    <form method="POST" action="{{ route('plugin.flashdeal.delete') }}">
                        @csrf
                        <input type="hidden" id="delete-deal-id" name="id">
                        <button type="button" class="btn long mt-2 btn-danger"
                            data-dismiss="modal">{{ translate('cancel') }}</button>
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
            $("#couponTable").DataTable({
                "responsive": false,
                "scrolX": true,
                "lengthChange": true,
                "autoWidth": false,
            }).buttons().container().appendTo('#couponTable_wrapper .col-md-6:eq(0)');
            // var bulk_actions_dropdown =
            //     '<div id="bulk-action" class="dataTables_length d-flex"><select class="theme-input-style bulk-action-selection mr-3"><option value="">{{ translate('Bulk Action') }}</option><option value="delete_all">{{ translate('Delete selection') }}</option></select><button class="btn long bulk-action-apply-btn">{{ translate('Apply') }}</button></div>';

            // $(bulk_actions_dropdown).insertAfter("#couponTable_wrapper #couponTable_length");
            /**
             * 
             * Change  status 
             * 
             * */
            $('.change-status').on('click', function(e) {
                e.preventDefault();
                let $this = $(this);
                let id = $this.data('deal');
                $.post('{{ route('plugin.flashdeal.update.status') }}', {
                    _token: '{{ csrf_token() }}',
                    id: id
                }, function(data) {
                    location.reload();
                })

            });
            /**
             * 
             * Select all deals
             **/
            $('.select-all').on('change', function() {
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
            $('.bulk-action-apply-btn').on('click', function(e) {
                e.preventDefault();
                let action = $('.bulk-action-selection').val();
                if (action === 'delete_all') {
                    var selected_items = [];
                    $('input[name^="items"]:checked').each(function() {
                        selected_items.push($(this).val());
                    });
                    if (selected_items.length > 0) {
                        $.post('{{ route('plugin.flashdeal.delete.bulk') }}', {
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
             * Delete Coupon
             * 
             * */
            $('.delete-deal').on('click', function(e) {
                e.preventDefault();
                let $this = $(this);
                let id = $this.data('deal');
                $("#delete-deal-id").val(id);
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
        border-radius: 8px !important;
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
