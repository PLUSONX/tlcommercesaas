@extends('core::base.layouts.master')
@section('title')
    {{ translate('New Custom Notifications') }}
@endsection
@section('custom_css')
    <!--Select2-->
    <link rel="stylesheet" href="{{ asset('backend/assets/plugins/select2/select2.min.css') }}">
    <!-- <link rel="stylesheet" href="{{ asset('/public/backend/assets/plugins/select2/select2.min.css') }}"> -->
    <!--End select2-->
    <!--Editor-->
    <link href="{{ asset('backend/assets/plugins/summernote/summernote-lite.css') }}" rel="stylesheet" />
    <!-- <link href="{{ asset('/public/backend/assets/plugins/summernote/summernote-lite.css') }}" rel="stylesheet" /> -->
    <!--End editor-->
@endsection
@section('main_content')
    <div class="row">

        <div class="col-12">
            <div class="card bg-transparent mb-20">

                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="" style="font-size: 30px;">{{ translate('New Custom Notifications') }}</h4>
                            <div class="d-flex flex-wrap">
                                <a href="{{ route('plugin.tlcommercecore.marketing.custom.notification') }}"
                                    class="btn long btn-orange">{{ translate('All Notifications') }}</a>
                            </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="col-12">
            <div class="card mb-2" style="border-radius: 12px !important; overflow: hidden !important;">
                <!-- <div class="card-header border-bottom2 mb-20">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="font-20">{{ translate('New Custom Notifications') }}</h4>
                        <a href="{{ route('plugin.tlcommercecore.marketing.custom.notification') }}"
                            class="btn long">{{ translate('All Notifications') }}</a>
                    </div>

                </div> -->
                <div class="card-body">
                    <div>
                        <form action="{{ route('plugin.tlcommercecore.marketing.custom.notification.send') }}"
                            method="POST">
                            @csrf
                            <div class="form-row mb-20">
                                <div class="col-md-3">
                                    <label class="font-14 bold black">{{ translate('Send To') }} </label>
                                </div>
                                <div class="col-md-12">
                                    <select class="select-notification-user-type theme-input-style select-2" name="send_to">
                                        <option
                                            value="{{ config('tlecommercecore.custom_notification_receiver_type.all_customers') }}"
                                            @selected(old('send_to') == config('tlecommercecore.custom_notification_receiver_type.all_customers'))>
                                            {{ translate('All Customers') }}</option>
                                        <option
                                            value="{{ config('tlecommercecore.custom_notification_receiver_type.specific_customer') }}"
                                            @selected(old('send_to') == config('tlecommercecore.custom_notification_receiver_type.specific_customer'))>
                                            {{ translate('Specific Customers') }}</option>
                                        <option
                                            value="{{ config('tlecommercecore.custom_notification_receiver_type.all_users') }}"
                                            @selected(old('send_to') == config('tlecommercecore.custom_notification_receiver_type.all_users'))>
                                            {{ translate('All Users') }}</option>
                                        <option
                                            value="{{ config('tlecommercecore.custom_notification_receiver_type.specific_user') }}"
                                            @selected(old('send_to') == config('tlecommercecore.custom_notification_receiver_type.specific_user'))>
                                            {{ translate('Specific Users') }}
                                        </option>
                                        <option
                                            value="{{ config('tlecommercecore.custom_notification_receiver_type.specific_user_role') }}"
                                            @selected(old('send_to') == config('tlecommercecore.custom_notification_receiver_type.specific_user_role'))>
                                            {{ translate('Specific User Role') }}
                                        </option>
                                    </select>
                                    @if ($errors->has('send_to'))
                                        <div class="invalid-input">{{ $errors->first('send_to') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div
                                class="form-row mb-20 customer-selector {{ old('send_to') == config('tlecommercecore.custom_notification_receiver_type.specific_customer') ? '' : 'd-none' }}">
                                <div class="col-md-3">
                                    <label class="font-14 bold black">{{ translate('Select Customers') }} </label>
                                </div>
                                <div class="col-md-12">
                                    <select class="select-customers theme-input-style" name="customers[]" multiple>
                                        <option>{{ translate('Select Customers') }}</option>
                                    </select>
                                    @if ($errors->has('customers'))
                                        <div class="invalid-input">{{ $errors->first('customers') }}</div>
                                    @endif
                                </div>
                            </div>

                            <div
                                class="form-row mb-20 user-selector {{ old('send_to') == config('tlecommercecore.custom_notification_receiver_type.specific_user') ? '' : 'd-none' }}">
                                <div class="col-md-3">
                                    <label class="font-14 bold black">{{ translate('Select Users') }} </label>
                                </div>
                                <div class="col-md-12">
                                    <select class="select-users theme-input-style" name="users[]" multiple>
                                        <option>{{ translate('Select Users') }}</option>
                                    </select>
                                    @if ($errors->has('name'))
                                        <div class="invalid-input">{{ $errors->first('name') }}</div>
                                    @endif
                                </div>
                            </div>

                            <div
                                class="form-row mb-20 user-roles-selector {{ old('send_to') == config('tlecommercecore.custom_notification_receiver_type.specific_user_role') ? '' : 'd-none' }}">
                                <div class="col-md-3">
                                    <label class="font-14 bold black">{{ translate('Select User Roles') }} </label>
                                </div>
                                <div class="col-md-12">
                                    <select class="select-user-role theme-input-style" name="user_roles[]" multiple>
                                        <option>{{ translate('Select Users') }}</option>
                                    </select>
                                    @if ($errors->has('user_roles'))
                                        <div class="invalid-input">{{ $errors->first('user_roles') }}</div>
                                    @endif
                                </div>
                            </div>

                            <div class="form-row mb-20">
                                <div class="col-md-3">
                                    <label class="font-14 bold black">{{ translate('Notification Type') }} </label>
                                </div>
                                <div class="col-md-12">
                                    <select class="select-notification-notification-type theme-input-style select-2"
                                        name="notification_type">
                                        <option value="{{ config('tlecommercecore.custom_notification_type.dashboard') }}"
                                            @selected(old('notification_type') == config('tlecommercecore.custom_notification_type.dashboard'))>
                                            {{ translate('Dashboard') }}</option>
                                        <option value="{{ config('tlecommercecore.custom_notification_type.email') }}"
                                            @selected(old('notification_type') == config('tlecommercecore.custom_notification_type.email'))>
                                            {{ translate('Email') }}</option>
                                        <option
                                            value="{{ config('tlecommercecore.custom_notification_type.email_dashboard') }}"
                                            @selected(old('notification_type') == config('tlecommercecore.custom_notification_type.email_dashboard'))>
                                            {{ translate('Dashboard & Email') }}</option>
                                    </select>
                                    @if ($errors->has('notification_type'))
                                        <div class="invalid-input">{{ $errors->first('notification_type') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div
                                class="form-row mb-20 email-subject {{ old('notification_type') == config('tlecommercecore.custom_notification_type.email_dashboard') || old('notification_type') == config('tlecommercecore.custom_notification_type.email') ? '' : 'd-none' }}">
                                <div class="col-sm-3">
                                    <label class="font-14 bold black ">{{ translate('Subject') }} </label>
                                </div>
                                <div class="col-md-12">
                                    <div class="editor-wrap">
                                        <input type="text" class="theme-input-style" name="subject"
                                            value="{{ old('subject') }}"
                                            placeholder="{{ translate('Notification subject') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="form-row mb-20">
                                <div class="col-sm-3">
                                    <label class="font-14 bold black ">{{ translate('Message') }} </label>
                                </div>
                                <div class="col-md-12">
                                    <div class="editor-wrap">
                                        <textarea id="message" name="message">{{ old('message') }}</textarea>
                                    </div>
                                    @if ($errors->has('message'))
                                        <div class="invalid-input">{{ $errors->first('message') }}</div>
                                    @endif
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-md-12 text-right">
                                    <button type="submit" class="btn long btn-orange">{{ translate('Send Now') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
@section('custom_scripts')
    <!--Select2-->
    <script src="{{ asset('backend/assets/plugins/select2/select2.min.js') }}"></script>
    <!-- <script src="{{ asset('/public/backend/assets/plugins/select2/select2.min.js') }}"></script> -->
    <!--End Select2-->
    <!--Editor-->
    <script src="{{ asset('backend/assets/plugins/summernote/summernote-lite.js') }}"></script>
    <!-- <script src="{{ asset('/public/backend/assets/plugins/summernote/summernote-lite.js') }}"></script> -->
    <!--End Editor-->
    <script>
        (function($) {
            "use strict";
            $("#message").summernote({
                tabsize: 2,
                height: 200,
                codeviewIframeFilter: false,
                codeviewFilter: true,
                codeviewFilterRegex: /<\/*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|ilayer|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|t(?:itle|extarea)|xml)[^>]*>|on\w+\s*=\s*"[^"]*"|on\w+\s*=\s*'[^']*'|on\w+\s*=\s*[^\s>]+/gi,
                toolbar: [
                    ["style", ["style"]],
                    ["font", ["bold", "underline", "clear"]],
                    ["color", ["color"]],
                    ["para", ["ul", "ol", "paragraph"]],
                    ["table", ["table"]],
                    ["insert", ["link", "video"]],
                    ["view", ["fullscreen", "codeview", "help"]],
                ],
                callbacks: {
                    onChangeCodeview: function(contents, $editable) {
                        let code = $(this).summernote('code')
                        code = code.replace(
                            /<\/*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|ilayer|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|t(?:itle|extarea)|xml)[^>]*>|on\w+\s*=\s*"[^"]*"|on\w+\s*=\s*'[^']*'|on\w+\s*=\s*[^\s>]+/gi,
                            '')
                        $(this).val(code)
                    }
                }
            });
            /**
             *  Select notification user tyle
             * 
             */
            $('.select-2').select2({
                theme: "classic",
            });
            /**
             * Select customer
             * 
             */
            $('.select-customers').select2({
                theme: "classic",
                placeholder: '{{ translate('Select customers') }}',
                closeOnSelect: false,
                ajax: {
                    url: '{{ route('plugin.tlcommercecore.marketing.custom.notification.customer.options') }}',
                    dataType: 'json',
                    method: "GET",
                    delay: 250,
                    data: function(params) {
                        return {
                            term: params.term || '',
                            page: params.page || 1
                        }
                    },
                    cache: true
                }
            });
            /**
             *Select users 
             */
            $('.select-users').select2({
                theme: "classic",
                placeholder: '{{ translate('Select users') }}',
                allowClear: true,
                closeOnSelect: false,
                ajax: {
                    url: '{{ route('plugin.tlcommercecore.marketing.custom.notification.users.options') }}',
                    dataType: 'json',
                    method: "GET",
                    delay: 250,
                    data: function(params) {
                        return {
                            term: params.term || '',
                            page: params.page || 1
                        }
                    },
                    cache: true
                }
            });
            /**
             *Select users  role
             */
            $('.select-user-role').select2({
                theme: "classic",
                placeholder: '{{ translate('Select user roles') }}',
                allowClear: true,
                closeOnSelect: false,
                ajax: {
                    url: '{{ route('plugin.tlcommercecore.marketing.custom.notification.user.roles.options') }}',
                    dataType: 'json',
                    method: "GET",
                    delay: 250,
                    data: function(params) {
                        return {
                            term: params.term || '',
                            page: params.page || 1
                        }
                    },
                    cache: true
                }
            });
            /**
             * Select notification user type
             * 
             */
            $(".select-notification-user-type").on('change', function(e) {
                e.preventDefault();
                let type = $(this).val();
                $('.select2-container--classic').addClass('w-100');
                if (type ==
                    {{ config('tlecommercecore.custom_notification_receiver_type.specific_customer') }}) {
                    $('.customer-selector').removeClass('d-none');
                    $('.user-selector').addClass('d-none');
                    $('.user-roles-selector').addClass('d-none');
                } else if (type ==
                    {{ config('tlecommercecore.custom_notification_receiver_type.specific_user') }}) {
                    $('.customer-selector').addClass('d-none');
                    $('.user-roles-selector').addClass('d-none');
                    $('.user-selector').removeClass('d-none');
                } else if (type ==
                    {{ config('tlecommercecore.custom_notification_receiver_type.specific_user_role') }}) {
                    $('.customer-selector').addClass('d-none');
                    $('.user-selector').addClass('d-none');
                    $('.user-roles-selector').removeClass('d-none');
                } else {
                    $('.customer-selector').addClass('d-none');
                    $('.user-selector').addClass('d-none');
                    $('.user-roles-selector').addClass('d-none');

                }
            });
            /**
             * Select notification type
             * 
             */
            $('.select-notification-notification-type').on('change', function(e) {
                e.preventDefault();
                let type = $(this).val();
                $('.select2-container--classic').addClass('w-100');
                if (type == {{ config('tlecommercecore.custom_notification_type.email') }} || type ==
                    {{ config('tlecommercecore.custom_notification_type.email_dashboard') }}) {
                    $('.email-subject').removeClass('d-none');
                } else {
                    $('.email-subject').addClass('d-none');
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
            box-shadow: none !important;
            border-radius: 6px !important;
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
            background: #ff5A1f !important;
            border-color: #e07b00 !important;
            box-shadow: none !important;
            outline: none !important;
        }

            /* Target the nav-link only when it has the .active class */
        .nav-link.active {
            background-color: #ff5a1f !important;
            color: #ffffff !important;
            border-radius: 12px; /* Optional: adds a slight curve to the background */
            width: 75%;
            margin-left: 10px;
        }

        /* Optional: Ensure the text stays white if there is a hover state */
        .nav-link.active:hover {
            color: #ffffff;
            opacity: 0.9;
        }

        .nav-link:hover {
            color: #ff5a1f !important;
            opacity: 0.9;
        }

        .nav-link.active i {
            color: #ffffff !important;
        }

        /* 2. Target the icon when hovering over a non-active link */
        .nav-link:hover i {
            color: #ff5a1f !important; 
            transition: color 0.3s ease;
        }

        /* 3. Ensure general icon alignment */
        .nav-link i {
            color: #666; /* Default grey color for inactive icons */
            vertical-align: middle;
            margin-right: 8px; /* Space between icon and text */
        }

        .menu-settings-title {

                color: #4A5565 !important;

            }

            .nav-link.active .menu-settings-title {
                color: #ffffff !important;
            }

            /* Optional: If you want the text to turn orange on hover for inactive tabs */
            .nav-link:hover .menu-settings-title {
                color: #ff5a1f !important;
            }

            .input-icon {
            width: 18px;
            height: 18px;
            stroke: #000000;      /* Use stroke for Lucide SVG icons */
            fill: none;           /* Ensure it's not filled in */
            pointer-events: none;
            z-index: 2;
            transition: stroke 0.3s ease; /* Smooth color swap */
        }

        /* 1. Only hover-orange if NOT active */
        .nav-link:not(.active):hover .menu-settings-title {
            color: #ff5a1f !important;
        }

        .nav-link:not(.active):hover .input-icon {
            stroke: #ff5a1f !important;
        }

        /* 2. Lock the white color when the link IS active (even on hover) */
        .nav-link.active:hover .menu-settings-title {
            color: #ffffff !important;
            opacity: 1; /* Prevents the fade effect if you don't want it */
        }

        .nav-link.active:hover .input-icon {
            stroke: #ffffff !important;
            opacity: 1;
        }

        .nav-link.active .input-icon {
            stroke: #ffffff !important;
            opacity: 1;
        }

        /* 3. Keep the background orange when active-hovered */
        .nav-link.active:hover {
            background-color: #ff5a1f !important;
        }

        .theme-input-style {
        width: 100%;
        background-color: white !important;
        border: 1px solid black !important;
    }

    .theme-input-style:focus, 
    .theme-input-style:active,
    .theme-input-style:hover {
        background-color: white !important;
        background: white !important; /* Extra insurance */
        outline: none;                /* Optional: removes default browser glow */
        border: 1px solid black !important; /* Keeps your border consistent */
    }

        @media (max-width: 900px) {
            /* Reset padding and margin for all links to prevent horizontal overflow */
            .nav-link {
                margin-left: 10px !important; /* Give them some touch space */
                margin: 5px 0; /* Vertical spacing between links */
                width: 100%;   /* Default to full width on mobile */
            }

            .nav-link.active {
                background-color: #ff5a1f !important;
                color: #ffffff !important;
                border-radius: 12px;
                width: 95%;      /* Nearly full width but with a little breathing room */
                margin-left: auto;
                margin-right: auto; /* Center the active tab */
                display: block;     /* Ensure width applies correctly */
            }

            /* Ensure text visibility on mobile hover */
            .nav-link.active:hover {
                color: #ffffff !important;
            }

            .theme-option-tab-wrap {
                padding-right: 0px;
                margin-right: 0px;

            }

            
        }
</style> 