@extends('core::base.layouts.master')
@section('title')
    {{ translate('New Deal') }}
@endsection
@section('main_content')
    <div class="row">

        <div class="col-md-8 offset-2">
            <div class="card bg-transparent mb-20">

                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="" style="font-size: 30px;">{{ translate('New Deal') }}</h4>
                          
                    </div>
                </div>

            </div>
        </div>
        <!-- <div class="col-lg-7 mx-auto"> -->
        <div class="col-md-8 offset-2">
            <div class="card mb-2" style="border-radius: 12px !important; overflow: hidden !important;">
            <!-- <div class="mb-3">
                <p class="alert alert-info">You are inserting <strong>"{{ getLanguageNameByCode(getDefaultLang()) }}"</strong> version</p>
            </div> -->
                <div class="form-element py-30">
                    <!-- <h4 class="font-20 mb-30">{{ translate('New Deal') }}</h4> -->

                    <form action="{{ route('plugin.flashdeal.store.new') }}" method="POST">
                        @csrf
                        <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold black">{{ translate('Title') }} </label>
                            </div>
                            <div class="col-md-12">
                                <input type="text" name="title" class="theme-input-style"
                                    value="{{ old('title') }}" placeholder="{{ translate('Type title') }}">
                                <input type="hidden" name="permalink" id="permalink_input_field">
                                @if ($errors->has('title'))
                                    <div class="invalid-input">{{ $errors->first('title') }}</div>
                                @endif
                            </div>
                        </div>
                        <!---Permalink---->
                        <div
                            class="form-row mb-20 permalink-input-group d-none @if ($errors->has('permalink')) d-flex @endif">
                            <div class="col-sm-4">
                                <label class="font-14 bold black">{{ translate('Permalink') }} </label>
                            </div>
                            <div class="col-sm-8">
                                <a href="#">{{ url('') }}/<span id="permalink">{{ old('permalink') }}</span><span
                                        class="btn custom-btn ml-1 permalink-edit-btn"
                                        style="box-shadow: none !important;"
                                        >{{ translate('Edit') }}</span></a>
                                @if ($errors->has('permalink'))
                                    <div class="invalid-input">{{ $errors->first('permalink') }}</div>
                                @endif
                                <div class="permalink-editor d-none">
                                    <input type="text" class="theme-input-style" id="permalink-updated-input"
                                        placeholder="{{ translate('Type here') }}">
                                    <button type="button" class="btn long mt-2 btn-danger permalink-cancel-btn"
                                        data-dismiss="modal">{{ translate('Cancel') }}</button>
                                    <button type="button"
                                        class="btn long mt-2 permalink-save-btn">{{ translate('Save') }}</button>
                                </div>
                            </div>
                        </div>
                        <!---End Permalink---->
                        <!-- <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold black">{{ translate('Background Color') }} </label>
                            </div>
                            <div class="col-md-12">
                                <div class="input-group addon">
                                    <input type="text" name="background_color" class="color-input form-control style--two"
                                        placeholder="#fffff" value="#FFFFF">
                                    <div class="input-group-append">
                                        <input type="color" class="input-group-text theme-input-style2 color-picker"
                                            id="colorPicker" value="#fffff" oninput="selectColor(event,this.value)">
                                    </div>
                                </div>
                                @if ($errors->has('background_color'))
                                    <div class="invalid-input">{{ $errors->first('background_color') }}</div>
                                @endif
                            </div>
                        </div> -->

                        
                        <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold black">{{ translate('Start date') }} </label>
                            </div>
                            <div class="col-md-12">
                                <input type="datetime-local" name="start_date" class="theme-input-style"
                                    value="{{ old('start_date') }}">
                                @if ($errors->has('start_date'))
                                    <div class="invalid-input">{{ $errors->first('start_date') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold black">{{ translate('Expiry date') }} </label>
                            </div>
                            <div class="col-md-12">
                                <input type="datetime-local" name="expiry_date" class="theme-input-style"
                                    value="{{ old('expiry_date') }}">
                                @if ($errors->has('expiry_date'))
                                    <div class="invalid-input">{{ $errors->first('expiry_date') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold black">{{ translate('Background Color') }}</label>
                            </div>
                            <div class="col-md-12">
                                <div class="input-group addon">
                                    <input 
                                        type="text" 
                                        name="background_color" 
                                        class="color-input form-control theme-input-style"
                                        placeholder="#ffffff" 
                                        value="#FFFFFF"
                                    >
                                    <div class="input-group-append">
                                        <input 
                                            type="color" 
                                            class="input-group-text color-picker" 
                                            id="colorPicker" 
                                            value="#ffffff" 
                                            oninput="selectColor(event, this.value)"
                                        >
                                    </div>
                                </div>
                                @if ($errors->has('background_color'))
                                    <div class="invalid-input">{{ $errors->first('background_color') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold black">{{ translate('Text Color') }} </label>
                            </div>
                            <div class="col-md-12">
                                <div class="input-group addon">
                                    <input type="text" name="background_color" class="color-input form-control theme-input-style"
                                        placeholder="#fffff" value="#FFFFF">
                                    <div class="input-group-append">
                                        <input type="color" class="input-group-text color-picker"
                                            id="colorPicker" value="#fffff" oninput="selectColor(event,this.value)">
                                    </div>
                                </div>
                                @if ($errors->has('text_color'))
                                    <div class="invalid-input">{{ $errors->first('text_color') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold black">{{ translate('Banner') }}</label>
                            </div>
                            <div class="col-md-12">
                                @include('core::base.includes.media.media_input', [
                                    'input' => 'banner',
                                    'data' => old('banner'),
                                ])
                                @if ($errors->has('banner'))
                                    <div class="invalid-input">{{ $errors->first('banner') }}</div>
                                @endif
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
    </div>
    @include('core::base.media.partial.media_modal')
@endsection
@section('custom_scripts')
    <script>
        (function($) {
            "use strict";
            /**
             * 
             * Media Library
             * */
            initDropzone()
            $(document).ready(function() {
                is_for_browse_file = true
                filtermedia()

            });

            /*Generate permalink*/
            $('.deal_title').change(function(e) {
                e.preventDefault();
                let name = $('.deal_title').val();
                let permalink = string_to_slug(name);
                $('#permalink').html(permalink);
                $('#permalink_input_field').val(permalink);
                $('.permalink-input-group').removeClass("d-none");
                $('.permalink-editor').addClass("d-none");
                $('.permalink-edit-btn').removeClass("d-none");

            });
            /*edit permalink*/
            $('.permalink-edit-btn').on('click', function(e) {
                e.preventDefault();
                let permalink = $('#permalink').html();
                $('#permalink-updated-input').val(permalink);
                $('.permalink-edit-btn').addClass("d-none");
                $('.permalink-editor').removeClass("d-none");


            });
            /*Cancel permalink edit*/
            $('.permalink-cancel-btn').on('click', function(e) {
                e.preventDefault();
                $('#permalink-updated-input').val();
                $('.permalink-editor').addClass("d-none");
                $('.permalink-edit-btn').removeClass("d-none");

            });

            /*Update permalink*/
            $('.permalink-save-btn').on('click', function(e) {
                e.preventDefault();
                let input = $('#permalink-updated-input').val();
                let updated_permalnk = string_to_slug(input);
                $('#permalink_input_field').val(updated_permalnk);
                $('#permalink').html(updated_permalnk);
                $('.permalink-editor').addClass("d-none");
                $('.permalink-edit-btn').removeClass("d-none");

            });

        })(jQuery);

        //Select color
        function selectColor(e, color) {
            "use strict";
            let target = e.target;
            $(target).closest('.addon').find('.color-input').val(color);
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

    .theme-input-style {
    /* padding-left: 40px !important;  */
    width: 100%;
    background: white !important;
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

    /* 4. Optional: Style the Focus state (when clicked) to remove the blue shadow */
    /* .pagination .page-item .page-link:focus {
        box-shadow: 0 0 0 0.2rem rgba(255, 90, 31, 0.25);
    } */

</style>