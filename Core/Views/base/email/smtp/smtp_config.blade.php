@extends('core::base.layouts.master')
@section('title')
    {{ translate('Smtp Configuration') }}
@endsection

@section('main_content')
    <div class="row">
        <div class="col-lg-6">
            <div class="card mb-2" style="border-radius: 12px !important; overflow: hidden !important;">
                <div class="card-header bg-white border-bottom2">
                    <h4>{{ translate('Email Configuration') }}</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('core.email.update.smtp.configuration') }}" method="POST">
                        @csrf
                        <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold black">{{ translate('Type') }} </label>
                            </div>
                            <div class="col-md-12">
                                <select name="mail_driver" class="theme-input-style mail_driver">
                                    <option value="smtp" @if ($mail_driver == 'smtp') selected @endif>
                                        {{ translate('smtp') }}</option>
                                </select>
                                @if ($errors->has('mail_host'))
                                    <div class="invalid-input">{{ $errors->first('mail_host') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="setup-send-mail-smtp">
                            <div class="form-row mb-20">
                                <div class="col-sm-4">
                                    <label class="font-14 bold black">{{ translate('MAIL HOST') }} </label>
                                </div>
                                <div class="col-md-12">
                                    <input type="text" name="mail_host" class="theme-input-style"
                                        placeholder="{{ translate('Type here') }}" value="{{ $mail_host }}">
                                    @if ($errors->has('mail_host'))
                                        <div class="invalid-input">{{ $errors->first('mail_host') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-row mb-20">
                                <div class="col-sm-4">
                                    <label class="font-14 bold black">{{ translate('MAIL PORT') }} </label>
                                </div>
                                <div class="col-md-12">
                                    <input type="text" name="mail_port" class="theme-input-style"
                                        placeholder="{{ translate('Type here') }}" value="{{ $mail_port }}">
                                    @if ($errors->has('mail_port'))
                                        <div class="invalid-input">{{ $errors->first('mail_port') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-row mb-20">
                                <div class="col-sm-4">
                                    <label class="font-14 bold black">{{ translate('MAIL USERNAME') }} </label>
                                </div>
                                <div class="col-md-12">
                                    <input type="text" name="mail_user_name" class="theme-input-style"
                                        placeholder="{{ translate('Type here') }}" value="{{ $mail_user_name }}">
                                    @if ($errors->has('mail_user_name'))
                                        <div class="invalid-input">{{ $errors->first('mail_user_name') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-row mb-20">
                                <div class="col-sm-4">
                                    <label class="font-14 bold black">{{ translate('MAIL PASSWORD') }} </label>
                                </div>
                                <div class="col-md-12">
                                    <input type="text" name="mail_password" class="theme-input-style"
                                        placeholder="{{ translate('Type here') }}" value="{{ $mail_password }}">
                                    @if ($errors->has('mail_password'))
                                        <div class="invalid-input">{{ $errors->first('mail_password') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-row mb-20">
                                <div class="col-sm-4">
                                    <label class="font-14 bold black">{{ translate('MAIL ENCRYPTION') }} </label>
                                </div>
                                <div class="col-md-12">
                                    <input type="text" name="mail_encryption" class="theme-input-style"
                                        placeholder="{{ translate('Type here') }}" value="{{ $mail_encryption }}">
                                    @if ($errors->has('mail_encryption'))
                                        <div class="invalid-input">{{ $errors->first('mail_encryption') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-row mb-20">
                                <div class="col-md-12">
                                    <label class="font-14 bold black">{{ translate('MAIL FROM ADDRESS') }} </label>
                                </div>
                                <div class="col-md-12">
                                    <input type="text" name="mail_from" class="theme-input-style"
                                        placeholder="{{ translate('Type here') }}" value="{{ $mail_from }}">
                                    @if ($errors->has('mail_from'))
                                        <div class="invalid-input">{{ $errors->first('mail_from') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-row mb-20">
                                <div class="col-sm-4">
                                    <label class="font-14 bold black">{{ translate('MAIL FROM NAME') }} </label>
                                </div>
                                <div class="col-md-12">
                                    <input type="text" name="mail_from_name" class="theme-input-style"
                                        placeholder="{{ translate('Type here') }}" value="{{ $mail_from_name }}">
                                    @if ($errors->has('mail_from_name'))
                                        <div class="invalid-input">{{ $errors->first('mail_from_name') }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-12 text-right">
                                <button type="submit" class="btn long btn-orange">{{ translate('Save Changes') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-6 mt-3 mt-lg-0">
            <div class="card mb-2" style="border-radius: 12px !important; overflow: hidden !important;">
                <div class="card-header bg-white border-bottom2">
                    <h4>{{ translate('Send Test Mail') }}</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('core.email.send.test') }}" method="POST">
                        @csrf

                        <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold black">{{ translate('Email') }} </label>
                            </div>
                            <div class="col-md-12">
                                <input type="email" name="email" class="theme-input-style"
                                    placeholder="{{ translate('Type here') }}">
                                @if ($errors->has('email'))
                                    <div class="invalid-input">{{ $errors->first('email') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold black">{{ translate('Subject') }} </label>
                            </div>
                            <div class="col-md-12">
                                <input type="text" name="subject" class="theme-input-style"
                                    placeholder="{{ translate('Type here') }}">
                                @if ($errors->has('subject'))
                                    <div class="invalid-input">{{ $errors->first('subject') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold black">{{ translate('Message') }} </label>
                            </div>
                            <div class="col-md-12">
                                <input type="text" name="message" class="theme-input-style"
                                    placeholder="{{ translate('Type here') }}">
                                @if ($errors->has('message'))
                                    <div class="invalid-input">{{ $errors->first('message') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-12 text-right">
                                <button type="submit" class="btn long btn-orange">{{ translate('Send') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('custom_scripts')
    <script>
        $('.mail_driver').on('change', function() {
            "use strict";
            let value = $('.mail_driver').val();
            if (value == 'smtp' || value == 'sendmail') {
                $('.main-gun-setup').addClass('d-none');
            } else {
                $('.main-gun-setup').removeClass('d-none');
            }
        });
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

      .btn-link {
        color: #ff5A1f !important;
    }


</style>