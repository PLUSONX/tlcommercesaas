@php
    $image_types = getAllImageTypes();
    $placeholder_info = getPlaceHolderImage();
    $placeholder_image = '';
    $placeholder_image_alt = '';

    if ($placeholder_info != null) {
        $placeholder_image = $placeholder_info->placeholder_image;
        $placeholder_image_alt = $placeholder_info->placeholder_image_alt;
    }

@endphp
@extends('core::base.layouts.master')
@section('title')
    {{ translate('Media Settings') }}
@endsection
@section('custom_css')
    <link rel="stylesheet" href="{{ asset('backend/assets/plugins/select2/select2.min.css') }}">
    <!-- <link rel="stylesheet" href="{{ asset('/public/backend/assets/plugins/select2/select2.min.css') }}"> -->
@endsection
@section('main_content')
    <!-- Image Settings -->
    <div class="row">
        <div class="col-12 col-md-8 offset-md-2">
            <div class="card bg-transparent mb-20">
                <div class="card-body">
                    <div class="d-flex justify-content-start justify-content-md-between align-items-center">
                        <h4 class="text-start" style="font-size: 30px;">{{ translate('Media Settings') }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8 mb-30 mx-auto">
            <div class="card" style="border-radius: 12px !important; overflow: hidden !important;">
                <!-- <div class="card-header bg-white py-3">
                    <h4 class="mb-0">{{ translate('Media Settings') }}</h4>
                </div> -->
                <div class="card-body">
                    <form action="{{ route('core.store.media.settings') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @if (!isTenant())
                            <div class="form-row mb-20">
                                <div class="col-sm-4">
                                    <label class="font-14 bold black">{{ translate('File Storage') }} </label>
                                </div>
                                <div class="col-md-12">
                                    <select name="file_storage" class="theme-input-style file_storage_option">
                                        <option value="public" @if (getGeneralSetting('file_storage') == 'public') selected @endif>
                                            {{ translate('Local') }}
                                        </option>
                                        <option value="amazons3" @if (getGeneralSetting('file_storage') == 'amazons3') selected @endif>
                                            {{ translate('Amazone S3') }}
                                        </option>
                                    </select>
                                    @if ($errors->has('file_storage'))
                                        <div class="invalid-input">{{ $errors->first('file_storage') }}</div>
                                    @endif
                                </div>
                            </div>
                            <!--Amazon s3 settings-->
                            <div
                                class="amazon-s3-setup {{ getGeneralSetting('file_storage') == 'amazons3' ? '' : 'd-none' }}">
                                <div class="form-row mb-20">
                                    <div class="col-sm-4">
                                        <label class="font-14 bold black">{{ translate('AWS ACCESS KEY ID') }}
                                        </label>
                                    </div>
                                    <div class="col-md-12">
                                        <input type="text" name="aws_access_key_id" class="theme-input-style"
                                            placeholder="{{ translate('Type here') }}"
                                            value="{{ env('AWS_ACCESS_KEY_ID') }}">
                                        @if ($errors->has('aws_access_key_id'))
                                            <div class="invalid-input">{{ $errors->first('aws_access_key_id') }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-row mb-20">
                                    <div class="col-sm-4">
                                        <label class="font-14 bold black">{{ translate('AWS SECRET ACCESS KEY') }}
                                        </label>
                                    </div>
                                    <div class="col-md-12">
                                        <input type="text" name="aws_secret_access_key" class="theme-input-style"
                                            placeholder="{{ translate('Type here') }}"
                                            value="{{ env('AWS_SECRET_ACCESS_KEY') }}">
                                        @if ($errors->has('aws_secret_access_key'))
                                            <div class="invalid-input">{{ $errors->first('aws_secret_access_key') }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-row mb-20">
                                    <div class="col-sm-4">
                                        <label class="font-14 bold black">{{ translate('AWS DEFAULT REGION') }}
                                        </label>
                                    </div>
                                    <div class="col-md-12">
                                        <input type="text" name="aws_default_region" class="theme-input-style"
                                            placeholder="{{ translate('Type here') }}"
                                            value="{{ env('AWS_DEFAULT_REGION') }}">
                                        @if ($errors->has('aws_default_region'))
                                            <div class="invalid-input">
                                                {{ $errors->first('aws_default_region') }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-row mb-20">
                                    <div class="col-sm-4">
                                        <label class="font-14 bold black">{{ translate('AWS BUCKET') }}
                                        </label>
                                    </div>
                                    <div class="col-md-12">
                                        <input type="text" name="aws_bucket" class="theme-input-style"
                                            placeholder="{{ translate('Type here') }}" value="{{ env('AWS_BUCKET') }}">
                                        @if ($errors->has('aws_bucket'))
                                            <div class="invalid-input">
                                                {{ $errors->first('aws_bucket') }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-row mb-20">
                                    <div class="col-sm-4">
                                        <label class="font-14 bold black">{{ translate('AWS URL') }}
                                        </label>
                                    </div>
                                    <div class="col-md-12">
                                        <input type="text" name="aws_endpoint" class="theme-input-style"
                                            placeholder="{{ translate('Type here') }}" value="{{ env('AWS_URL') }}">
                                        @if ($errors->has('aws_endpoint'))
                                            <div class="invalid-input">
                                                {{ $errors->first('aws_endpoint') }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <!--End Amazon s3 settings-->
                            <hr>
                        @else
                            <input type="hidden" name="file_storage"
                                value="{{ getGeneralSetting('file_storage') == 'public' ? 'public' : 'amazons3' }}">
                        @endif

                        <div class="form-row mb-20">
                            <div class="col-md-4">
                                <label class="font-14 bold black">{{ translate('Placeholder Image') }}</label>
                            </div>
                            <div class="col-md-12">
                                @include('core::base.includes.media.media_input', [
                                    'input' => 'placeholder_image',
                                    'data' => getGeneralSetting('placeholder_image'),
                                ])
                                @if ($errors->has('placeholder_image'))
                                    <div class="invalid-input">{{ $errors->first('placeholder_image') }}</div>
                                @endif
                            </div>
                        </div>
                        <hr>
                        <h4 class="mb-4">{{ translate('Watermark Settings') }}</h4>
                        <div class="form-row mb-20">
                            <div class="col-md-4">
                                <label class="font-14 bold black">{{ translate('Enable/Disable Watermark') }}</label>
                            </div>
                            <div class="col-md-12">
                                <label class="switch glow primary medium">
                                    <input type="checkbox" name="watermark_status" id="watermark_status"
                                        @checked(getGeneralSetting('watermark_status') == 'on')>
                                    <span class="control"></span>
                                </label>
                            </div>
                        </div>

                        <div
                            class="form-row mb-20 watermark_image_settings {{ getGeneralSetting('watermark_status') == 'on' ? '' : 'd-none' }}">
                            <div class="col-md-4">
                                <label class="font-14 bold black">{{ translate('Watermark Image') }}</label>
                            </div>
                            <div class="col-md-12">
                                @include('core::base.includes.media.media_input', [
                                    'input' => 'watermark_image',
                                    'data' => getGeneralSetting('watermark_image'),
                                ])
                                @if ($errors->has('watermark_image'))
                                    <div class="invalid-input">{{ $errors->first('watermark_image') }}</div>
                                @endif
                            </div>
                        </div>

                        <div
                            class="form-row mb-20 watermark_image_settings {{ getGeneralSetting('watermark_status') == 'on' ? '' : 'd-none' }}">
                            <div class="col-md-4">
                                <label class="font-14 bold black">{{ translate('Watermark Image Position') }}</label>
                            </div>
                            <div class="col-md-12">
                                <select class="theme-input-style" name="watermark_image_position">
                                    <option value="top-left" class="text-uppercase" @selected(getGeneralSetting('watermark_image_position') == 'top-left')>
                                        {{ translate('Top Left') }}
                                    </option>
                                    <option value="top" class="text-uppercase" @selected(getGeneralSetting('watermark_image_position') == 'top')>
                                        {{ translate('Top') }}
                                    </option>
                                    <option value="top-right" class="text-uppercase" @selected(getGeneralSetting('watermark_image_position') == 'top-right')>
                                        {{ translate('Top Right') }}
                                    </option>
                                    <option value="left" class="text-uppercase" @selected(getGeneralSetting('watermark_image_position') == 'left')>
                                        {{ translate('Left') }}
                                    </option>
                                    <option value="center" class="text-uppercase" @selected(getGeneralSetting('watermark_image_position') == 'center')>
                                        {{ translate('Center') }}
                                    </option>
                                    <option value="right" class="text-uppercase" @selected(getGeneralSetting('watermark_image_position') == 'right')>
                                        {{ translate('Right') }}
                                    </option>
                                    <option value="bottom-left" class="text-uppercase" @selected(getGeneralSetting('watermark_image_position') == 'bottom-left')>
                                        {{ translate('Bottom Left') }}
                                    </option>
                                </select>
                                @if ($errors->has('watermark_image'))
                                    <div class="invalid-input">{{ $errors->first('watermark_image') }}</div>
                                @endif
                            </div>

                        </div>

                        <div
                            class="form-row mb-20 watermark_image_settings {{ getGeneralSetting('watermark_status') == 'on' ? '' : 'd-none' }}">
                            <div class="col-md-12">
                                <label
                                    class="font-14 bold black">{{ translate('Watermarking image opacity (%)') }}</label>
                            </div>
                            <div class="col-md-12">
                                <input type="number" name="water_marking_image_opacity" min="1"
                                    class="theme-input-style"
                                    value="{{ getGeneralSetting('water_marking_image_opacity') }}"
                                    placeholder="{{ translate('Watermarking image opacity') }}">
                                @if ($errors->has('water_marking_image_opacity'))
                                    <div class="invalid-input">{{ $errors->first('water_marking_image_opacity') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col-md-12 text-right">
                                <button type="submit" class="btn long">{{ translate('Submit') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @include('core::base.media.partial.media_modal')
    </div>
    <!-- /Image Settings -->
@endsection
@section('custom_scripts')
    <script src="{{ asset('backend/assets/plugins/select2/select2.min.js') }}"></script>
    <!-- <script src="{{ asset('/public/backend/assets/plugins/select2/select2.min.js') }}"></script> -->
    <script>
        (function($) {
            "use strict";
            initDropzone()
            $(document).ready(function() {
                is_for_browse_file = true
                filtermedia()
                //Select file storage system
                $('.file_storage_option').on('change', function(e) {
                    let value = $(this).val();
                    if (value == 'amazons3') {
                        $('.amazon-s3-setup').removeClass('d-none');
                    } else {
                        $('.amazon-s3-setup').addClass('d-none');
                    }
                });
                //Enable and disbale watermark image
                $('#watermark_status').on('change', function(e) {
                    if (!$('#watermark_status').is(":checked")) {
                        $('.watermark_image_settings').addClass('d-none');
                    } else {
                        $('.watermark_image_settings').removeClass('d-none');
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
        outline: none;                /* Optional: removes default browser glow */
        border: 1px solid black !important; /* Keeps your border consistent */
    }

      .btn-link {
        color: #ff5A1f !important;
    }


</style>