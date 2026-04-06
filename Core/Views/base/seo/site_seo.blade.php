@extends('core::base.layouts.master')
@section('title')
    {{ translate('Site Seo  Settings') }}
@endsection
@section('custom_css')
@endsection
@section('main_content')
    <div class="row">

        <div class="col-12 col-md-8 offset-md-2">
            <div class="card bg-transparent mb-20">
                <div class="card-body">
                    <div class="d-flex justify-content-start justify-content-md-between align-items-center">
                        <h4 class="text-start" style="font-size: 30px;">{{ translate('Site Seo Settings') }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-7 mb-30 mx-auto">
             <div class="card" style="border-radius: 12px !important; overflow: hidden !important;">
             <!--   <div class="card-header bg-white border-bottom2 pb-0">
                    <div class="post-head d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex align-items-center">
                            <div class="content">
                                <h4>{{ translate('Site Seo  Settings') }}</h4>
                            </div>
                        </div>
                    </div>
                </div> -->
                <div class="card-body">
                    <div>
                        <form action="{{ route('core.seo.settings.update') }}" method="POST">
                            @csrf
                            <div class="form-row mb-20">
                                <div class="col-md-4">
                                    <label class="font-14 bold black">{{ translate('Meta title') }}</label>
                                </div>
                                <div class="col-md-12">
                                    <input type="text" name="site_meta_title" class="theme-input-style"
                                        value="{{ getGeneralSetting('site_meta_title') }}"
                                        placeholder="{{ translate('Meta Title') }}">
                                    @if ($errors->has('site_meta_title'))
                                        <div class="invalid-input">{{ $errors->first('site_meta_title') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-row mb-20">
                                <div class="col-md-4">
                                    <label class="font-14 bold black">{{ translate('Meta description') }}</label>
                                </div>
                                <div class="col-md-12">
                                    <textarea name="site_meta_description" class="theme-input-style">{{ getGeneralSetting('site_meta_description') }}</textarea>
                                    @if ($errors->has('site_meta_description'))
                                        <div class="invalid-input">{{ $errors->first('site_meta_description') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-row mb-20">
                                <div class="col-md-4">
                                    <label class="font-14 bold black">{{ translate('Meta keywords') }}</label>
                                </div>
                                <div class="col-md-12">
                                    <textarea name="site_meta_keywords" class="theme-input-style">{{ getGeneralSetting('site_meta_keywords') }}</textarea>
                                    @if ($errors->has('site_meta_keywords'))
                                        <div class="invalid-input">{{ $errors->first('site_meta_keywords') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-row mb-20">
                                <div class="col-md-4">
                                    <label class="font-14 bold black">{{ translate('Meta image') }}</label>
                                </div>
                                <div class="col-md-12">
                                    @include('core::base.includes.media.media_input', [
                                        'input' => 'site_meta_image',
                                        'data' => getGeneralSetting('site_meta_image'),
                                    ])
                                    @if ($errors->has('site_meta_image'))
                                        <div class="invalid-input">{{ $errors->first('site_meta_image') }}</div>
                                    @endif
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-md-12 text-right">
                                    <button type="submit" class="btn long btn-orange">{{ translate('Save Changes') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @include('core::base.media.partial.media_modal')
    </div>
@endsection
@section('custom_scripts')
    <script>
        (function($) {
            "use strict";
            initDropzone()
            $(document).ready(function() {
                is_for_browse_file = true
                filtermedia()
            })
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