@extends('core::base.layouts.master')
@section('title')
    {{ translate('Custom Notifications') }}
@endsection
@section('custom_css')
@endsection
@section('main_content')
    <div class="row">
        <div class="col-12">
            <div class="card bg-transparent mb-20">

                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="" style="font-size: 30px;">{{ translate('Custom Notifications') }}</h4>
                            <div class="d-flex flex-wrap">
                                <a href="{{ route('plugin.tlcommercecore.marketing.custom.notification.create.new') }}"
                                    class="btn long btn-orange">{{ translate('Compose') }}</a>
                            </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="col-12 mb-20">
            <div class="card" style="border-radius: 12px !important; overflow: hidden !important;">
                <!-- <div class="card-body border-bottom2 mb-20">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="font-20">{{ translate('Custom Notifications') }}</h4>
                        <a href="{{ route('plugin.tlcommercecore.marketing.custom.notification.create.new') }}"
                            class="btn long">{{ translate('Compose') }}</a>
                    </div>

                </div> -->
                <div class=" mb-4">

                </div>

                <div class="px-2 filter-area mb-2">

                    <div class="row">
                        <!--Bulk actions-->
    
                        <div class="col-md-3 mb-3" style="margin-right: 0px !important; padding-right: 0px !important;">
                            
                            <select class="theme-input-style w-100" id="bulkActionSelector">
                                <option value="">
                                    {{ translate('Action') }}
                                </option>
                                <option value="all-delete">
                                    {{ translate('Delete Selected') }}
                                </option>
                            </select>
                            <!-- <button class="btn long btn-danger fire-bulk-action"
                                href="{{ route('plugin.tlcommercecore.orders.inhouse') }}" type="submit">{{ translate('Apply') }}
                            </button> -->
                            
                        </div>
    
                        <div class="col-md-3 mb-3" tyle="margin-left: 0px !important; padding-left: 0px !important;">
    
                             <button class="btn long btn-orange w-75"
                                href="{{ route('plugin.tlcommercecore.orders.inhouse') }}" type="submit">{{ translate('Apply') }}
                            </button>
                        </div>
    
                        <!--End bulk actions-->
                    </div>

                </div>

                <div class="table-responsive">
                    <!-- <table class="hoverable text-nowrap"> -->
                    <table class="hoverable">
                        <thead style="background: #F3F4F6;">
                            <tr>
                                <th>
                                    <label class="position-relative mr-2">
                                        <input type="checkbox" name="select_all" class="select-all">
                                        <span class="checkmark"></span>
                                    </label>
                                </th>
                                <th>{{ translate('To') }}</th>
                                <th>{{ translate('Type') }}</th>
                                <th>{{ translate('Sender') }}</th>
                                <th class="text-center">{{ translate('Message') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($notifications->count() > 0)
                                @foreach ($notifications as $key => $notification)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <label class="position-relative mr-2">
                                                    <input type="checkbox" name="items[]" class="item-id"
                                                        value="{{ $notification->id }}">
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                        </td>
                                        <td>
                                            @if ($notification->to == config('tlecommercecore.custom_notification_receiver_type.all_customers'))
                                                {{ translate('All Customers') }}
                                            @endif
                                            @if ($notification->to == config('tlecommercecore.custom_notification_receiver_type.specific_customer'))
                                                {{ translate('Specific Customers') }}
                                            @endif
                                            @if ($notification->to == config('tlecommercecore.custom_notification_receiver_type.all_users'))
                                                {{ translate('All Users') }}
                                            @endif
                                            @if ($notification->to == config('tlecommercecore.custom_notification_receiver_type.specific_user'))
                                                {{ translate('Specific Users') }}
                                            @endif
                                            @if ($notification->to == config('tlecommercecore.custom_notification_receiver_type.specific_user_role'))
                                                {{ translate('Specific User Roles') }}
                                            @endif
                                        </td>
                                        <td>
                                            @if ($notification->type == config('tlecommercecore.custom_notification_type.dashboard'))
                                                {{ translate('Dashbaord') }}
                                            @endif
                                            @if ($notification->type == config('tlecommercecore.custom_notification_type.email'))
                                                {{ translate('Email') }}
                                            @endif
                                            @if ($notification->type == config('tlecommercecore.custom_notification_type.email_dashboard'))
                                                {{ translate('Dashbaord & Email') }}
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                $user = \Core\Models\User::where('id', $notification->sender)->first();
                                            @endphp
                                            @if ($user != null)
                                                {{ $user->name }}
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <button class="btn sm btn-orange"
                                                onclick="viewDetails({{ json_encode($notification->details) }})">{{ translate('View Message') }}</button>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="7">
                                        <p class="alert alert-danger">{{ translate('Nothing Found') }}</p>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                    <div class="pgination px-3">
                        {!! $notifications->withQueryString()->onEachSide(1)->links('pagination::bootstrap-5-custom') !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--Details Modal-->
    <div id="details-modal" class="details-modal modal fade show" aria-modal="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title h6">{{ translate('Message') }}</h2>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                </div>
            </div>
        </div>
    </div>
    <!--Details Modal End-->
@endsection
@section('custom_scripts')
    <script>
        (function($) {
            "use strict";
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
            })
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
                        $.post('{{ route('plugin.tlcommercecore.marketing.custom.notification.bulk.action') }}', {
                            _token: '{{ csrf_token() }}',
                            data: data
                        }, function(data) {
                            if (data.success) {
                                toastr.success(
                                    '{{ translate('Selected items deleted successfully') }}',
                                    "Success");
                                location.reload();

                            } else {
                                toastr.error('{{ translate('Action Failed') }}', "Error!");
                            }
                        })
                    } else {
                        toastr.error('{{ translate('No Item Selected') }}', "Error!");
                    }
                } else {
                    toastr.error('{{ translate('No Action Selected') }}', "Error!");
                }

            });
        })(jQuery);
        /**
         *View details on notification
         * 
         **/
        function viewDetails(data) {
            "use strict";
            $('#details-modal').find('.modal-body').html(data);
            $('#details-modal').modal('show');
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

    .item-id, .select-all {
        position: absolute;
        opacity: 0;
        cursor: pointer;
        height: 0;
        width: 0;
    }

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
