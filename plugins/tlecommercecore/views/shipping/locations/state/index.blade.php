@extends('core::base.layouts.master')
@section('title')
    {{ translate('States') }}
@endsection
@section('custom_css')
@endsection
@section('main_content')
    <div class="row">

        <div class="col-12">
            <div class="card bg-transparent mb-20">

                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="" style="font-size: 30px;">{{ translate('States') }}</h4>
                            <div class="d-flex flex-wrap">
                                <a href="{{ route('plugin.tlcommercecore.shipping.locations.states.new.add') }}"
                                    class="btn long btn-orange">{{ translate('Add New State') }}</a>
                            </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="col-12">
            <div class="card mb-2" style="border-radius: 12px !important; overflow: hidden !important;">
                <!-- <div class="card-body border-bottom2 mb-20">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="font-20">{{ translate('States') }}</h4>
                        <div class="d-flex flex-wrap">
                            <a href="{{ route('plugin.tlcommercecore.shipping.locations.states.new.add') }}"
                                class="btn long">{{ translate('Add New State') }}</a>
                        </div>
                    </div>
                </div> -->
                <!-- <div class="px-2 filter-area d-flex align-items-center"> -->
                <div class="px-2 filter-area" style="margin-top: 35px;">
                    <!--Filter area-->
                    <form method="get" action="{{ route('plugin.tlcommercecore.shipping.locations.states.list') }}">


                        <div class="d-flex justify-content-between align-items-center">

                            <div class="col-md-3 mb-3">
                                <select class="theme-input-style w-100" name="per_page">
                                    <option value="">{{ translate('Per page') }}</option>
                                    <option value="10" @selected(request()->has('per_page') && request()->get('per_page') == '10')>10</option>
                                    <option value="20" @selected(request()->has('per_page') && request()->get('per_page') == '20')>20</option>
                                    <option value="50" @selected(request()->has('per_page') && request()->get('per_page') == '50')>50</option>
                                    <option value="all" @selected(request()->has('per_page') && request()->get('per_page') == 'all')>{{ translate('All') }}</option>
                                </select>
                            </div>


                            <div class="col-md-3 mb-3">

                                <input type="text" name="search_key" class="theme-input-style w-100"
                                    value="{{ request()->has('search_key') ? request()->get('search_key') : '' }}"
                                    placeholder="Enter state name">
                                <!-- <button type="submit" class="btn long">{{ translate('Filter') }}</button> -->

                            </div>

                            
                        </div>

                        <div class="d-flex justify-content-end align-items-end" style="margin-right: 20px; gap: 5px;">

                            @if (request()->has('search_key'))
                                <a class="btn long btn-danger" style="background: white !important; color: black !important; border: 1px solid black; border-radius: 6px !important; box-shadow: none !important;"
                                    href="{{ route('plugin.tlcommercecore.shipping.locations.states.list') }}">
                                    {{ translate('Clear Filter') }}
                                </a>
                            @endif

                            <button type="submit" class="btn long btn-orange">{{ translate('Filter') }}</button>
                            
                        </div>

                    </form>

                    <!-- @if (request()->has('search_key'))
                        <a class="btn long btn-danger"
                            href="{{ route('plugin.tlcommercecore.shipping.locations.states.list') }}">
                            {{ translate('Clear Filter') }}
                        </a>
                    @endif -->
                    <!--End filter area-->
                    <!--Bulk actions-->
                    <!-- <select class="theme-input-style bulk-action-selection">
                        <option value="null">{{ translate('Bulk Action') }}</option>
                        <option value="active">{{ translate('Make Active') }}</option>
                        <option value="in_active">{{ translate('Make Inactive') }}</option>
                        <option value="delete_all">{{ translate('Delete selection') }}</option>
                    </select>
                    <button class="btn long btn-warning fire-bulk-action">{{ translate('Apply') }}
                    </button> -->
                    <!--End bulk actions-->
                </div>
                <div class="table-responsive">
                    <table id="state_table" class="hoverable text-nowrap">
                        <thead style="background: #F3F4F6;">
                            <tr>
                                <!-- <th>
                                    <div class="d-flex align-items-center">
                                        <label class="position-relative">
                                            <input type="checkbox" name="select_all" class="checked-all-items">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                </th> -->
                                <th class="text-center">{{ translate('Name') }}</th>
                                <th class="text-center">{{ translate('Code') }}</th>
                                <th class="text-center">{{ translate('Country') }}</th>
                                <th >{{ translate('Status') }}</th>
                                <th class="text-center">{{ translate('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($states->count() > 0)
                                @foreach ($states as $key => $state)
                                    <tr>
                                        <!-- <td>
                                            <div class="d-flex align-items-center mb-3">
                                                <label class="position-relative mr-2">
                                                    <input type="checkbox" name="item_id[]" class="item-id"
                                                        value="{{ $state->id }}">
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                        </td> -->
                                        <td class="text-center">{{ $state->translation('name') }}</td>
                                        <td class="text-uppercase text-center">{{ $state->code }}</td>
                                        <td class="text-center">
                                            @if ($state->country != null)
                                                {{ $state->country->translation('name') }}
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <label class="switch glow primary medium">
                                                <input type="checkbox" class="change-status"
                                                    data-state="{{ $state->id }}" @checked($state->status == config('settings.general_status.active'))>
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
                                                        href="{{ route('plugin.tlcommercecore.shipping.locations.states.edit', ['id' => $state->id, 'lang' => getDefaultLang()]) }}">
                                                        {{ translate('Edit') }}
                                                    </a>
                                                    <a href="#" class="delete-state"
                                                        data-state="{{ $state->id }}">{{ translate('Delete') }}</a>
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
                        {!! $states->withQueryString()->onEachSide(1)->links('pagination::bootstrap-5-custom') !!}
                    </div>
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
                    <form method="POST" action="{{ route('plugin.tlcommercecore.shipping.locations.states.delete') }}">
                        @csrf
                        <input type="hidden" id="delete-state-id" name="id">
                        <button type="button" class="btn long btn-danger mt-2"
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
    <script>
        (function($) {
            "use strict";
            /**
             * 
             * Change state status 
             * 
             * */
            $('.change-status').on('click', function(e) {
                e.preventDefault();
                let $this = $(this);
                let id = $this.data('state');
                $.post('{{ route('plugin.tlcommercecore.shipping.locations.states.status.change') }}', {
                    _token: '{{ csrf_token() }}',
                    id: id
                }, function(data) {
                    location.reload();
                })
            });
            /**
             * 
             * Delete State
             * 
             * */
            $('.delete-state').on('click', function(e) {
                e.preventDefault();
                let $this = $(this);
                let id = $this.data('state');
                $("#delete-state-id").val(id);
                $('#delete-modal').modal('show');
            });

            /**
             * 
             * Checked all items
             **/
            $('.checked-all-items').on('change', function(e) {
                if ($('.checked-all-items').is(":checked")) {
                    $(".item-id").prop("checked", true);
                } else {
                    $(".item-id").prop("checked", false);
                }
            });
            /**
             * 
             * Bulk action
             **/
            $('.fire-bulk-action').on('click', function(e) {
                let action = $('.bulk-action-selection').val();
                if (action != 'null') {
                    var selected_items = [];
                    $('input[name^="item_id"]:checked').each(function() {
                        selected_items.push($(this).val());
                    });
                    if (selected_items.length > 0) {
                        $.post('{{ route('plugin.tlcommercecore.shipping.locations.states.bulk.action') }}', {
                            _token: '{{ csrf_token() }}',
                            items: selected_items,
                            action: action
                        }, function(data) {
                            if (data.success) {
                                toastr.success('{{ translate('Action Applied Successfully') }}');
                                location.reload();
                            }
                            if (!data.success) {
                                toastr.error('{{ translate('Action Failed') }}');
                            }
                        })
                    } else {
                        toastr.error('{{ translate('No Item Selected') }}');
                    }
                } else {
                    toastr.error('{{ translate('No Action Selected') }}');
                }
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
