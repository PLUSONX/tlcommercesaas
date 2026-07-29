@php
    $tzlist = DateTimeZone::listIdentifiers(DateTimeZone::ALL);
    $active_langs = getAllLanguages();
    $placeholder_info = getPlaceHolderImage();
    $placeholder_image = '';
    $placeholder_image_alt = '';

    if ($placeholder_info != null) {
        $placeholder_image = $placeholder_info->placeholder_image;
        $placeholder_image_alt = $placeholder_info->placeholder_image_alt;
    }

    $logo_path = $data['white_mobile_background_logo'] ?? $placeholder_image;
    $logo_path = preg_replace('#^/public#', '', $logo_path);

@endphp
@extends('core::base.layouts.master')
@section('title')
    {{ translate('General Settings') }}
@endsection
@section('custom_css')
    <!-- <link rel="stylesheet" href="{{ asset('/public/backend/assets/plugins/select2/select2.min.css') }}">
    <link href="{{ asset('/public/backend/assets/plugins/summernote/summernote-lite.css') }}" rel="stylesheet" /> -->
     <link rel="stylesheet" href="{{ asset('backend/assets/plugins/select2/select2.min.css') }}">
    <link href="{{ asset('backend/assets/plugins/summernote/summernote-lite.css') }}" rel="stylesheet" />
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

        .general-settings-submit {
            text-align: right;
        }
    </style>
@endsection
@section('main_content')
    <!-- General settings form -->
    <div class="row general-settings-page">

        <!-- <div class="col-md-8 offset-2">
            <div class="card bg-transparent mb-20">

                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="" style="font-size: 30px;">{{ translate('General Settings') }}</h4>
                          
                    </div>
                </div>

            </div>
        </div> -->

        <div class="col-12 col-md-8 offset-md-2">
            <div class="card bg-transparent mb-20">
                <div class="card-body">
                    <div class="d-flex justify-content-start justify-content-md-between align-items-center">
                        <h4 style="font-size: 30px;">{{ translate('General Settings') }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-7 mb-30 mx-auto">
            <div class="card mb-2" style="border-radius: 12px !important; overflow: hidden !important;">
                <!-- <div class="card-header bg-white border-bottom2 pb-0">
                    <div class="post-head d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex align-items-center">
                            <div class="content">
                                <h4>{{ translate('General Settings') }}</h4>
                            </div>
                        </div>
                    </div>
                </div> -->
                <div class="card-body">
                    <div>
                        <form class="general-settings-form" action="{{ route('core.store.general.settings') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="form-row mb-20">
                                <div class="col-md-4">
                                    <label class="font-14 bold black text-capitalize">{{ translate('Site Title') }}</label>
                                </div>
                                <div class="col-md-12">
                                    <input type="text" name="system_name" class="theme-input-style"
                                        value="{{ isset($data['system_name']) ? $data['system_name'] : '' }}"
                                        placeholder="{{ translate('Site Title') }}">
                                    @if ($errors->has('system_name'))
                                        <div class="invalid-input">{{ $errors->first('system_name') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-row mb-20">
                                <div class="col-md-4">
                                    <label class="font-14 bold black">{{ translate('Site Motto') }}</label>
                                </div>
                                <div class="col-md-12">
                                    <input type="text" name="site_moto" class="theme-input-style"
                                        value="{{ isset($data['site_moto']) ? $data['site_moto'] : '' }}"
                                        placeholder="{{ translate('Site Moto') }}">
                                    @if ($errors->has('site_moto'))
                                        <div class="invalid-input">{{ $errors->first('site_moto') }}</div>
                                    @endif
                                </div>
                            </div>

                            <div class="form-row mb-20">
                                <div class="col-md-4">
                                    <label class="font-14 bold black">{{ translate('Logo') }}</label>
                                </div>
                                <div class="col-md-12">
                                    <input type="hidden" name="white_background_logo" id="white_background_logo_id"
                                        value="{{ isset($data['white_background_logo_id']) ? $data['white_background_logo_id'] : '' }}">
                                    <div class="image-box">
                                        <div class="d-flex flex-wrap gap-10 mb-3">
                                            @if (isset($data['white_background_logo']))
                                                <div class="preview-image-wrapper">
                                                    <img src="{{ project_asset($data['white_background_logo']) }}"
                                                        width="150" class="preview_image"
                                                        id="white_background_logo_preview" />
                                                    <button type="button" title="Remove image"
                                                        class="remove-btn style--three" id="white_background_logo_remove"
                                                        onclick="removeSelection('#white_background_logo_preview,#white_background_logo_id,#white_background_logo_remove')"><i
                                                            class="icofont-close"></i></button>
                                                </div>
                                            @else
                                                <div class="preview-image-wrapper">
                                                     <img src="{{  project_asset($logo_path) }}" width="150"
                                                        class="preview_image" id="white_background_logo_preview" />
                                                    <!-- <img src="{{ project_asset($placeholder_image) }}" width="150"
                                                        class="preview_image" id="white_background_logo_preview" /> -->

                                                    <button type="button" title="Remove image"
                                                        class="remove-btn style--three d-none"
                                                        id="white_background_logo_remove"
                                                        onclick="removeSelection('#white_background_logo_preview,#white_background_logo_id,#white_background_logo_remove')"><i
                                                            class="icofont-close"></i></button>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="image-box-actions">
                                            <button type="button" class="btn-link" data-toggle="modal"
                                                data-target="#mediaUploadModal" id="white_background_logo_choose"
                                                onclick="setDataInsertableIds('#white_background_logo_preview,#white_background_logo_id,#white_background_logo_remove')">
                                                {{ translate('Choose image') }}
                                            </button>
                                        </div>
                                    </div>
                                    @if ($errors->has('white_background_logo'))
                                        <div class="invalid-input">{{ $errors->first('white_background_logo') }}</div>
                                    @endif
                                </div>
                            </div>

                            <div class="form-row mb-20">
                                <div class="col-md-4">
                                    <label class="font-14 bold black">{{ translate('Logo (Mobile)') }}</label>
                                </div>
                                <div class="col-md-12">
                                    <input type="hidden" name="white_mobile_background_logo"
                                        id="white_mobile_background_logo_id"
                                        value="{{ isset($data['white_mobile_background_logo_id']) ? $data['white_mobile_background_logo_id'] : '' }}">
                                    <div class="image-box">
                                        <div class="d-flex flex-wrap gap-10 mb-3">
                                            @if (isset($data['white_mobile_background_logo']))
                                                <div class="preview-image-wrapper">
                                                    <img src="{{ project_asset($data['white_mobile_background_logo']) }}"
                                                        width="150" class="preview_image"
                                                        id="white_mobile_background_logo_preview" />
                                                    <button type="button" title="Remove image"
                                                        class="remove-btn style--three"
                                                        id="white_mobile_background_logo_remove"
                                                        onclick="removeSelection('#white_mobile_background_logo_preview,#white_mobile_background_logo_id,#white_mobile_background_logo_remove')"><i
                                                            class="icofont-close"></i></button>
                                                </div>
                                            @else
                                                <div class="preview-image-wrapper">
                                                    <img src="{{ project_asset($placeholder_image) }}" width="150"
                                                        class="preview_image" id="white_mobile_background_logo_preview" />
                                                    <button type="button" title="Remove image"
                                                        class="remove-btn style--three d-none"
                                                        id="white_mobile_background_logo_remove"
                                                        onclick="removeSelection('#white_mobile_background_logo_preview,#white_mobile_background_logo_id,#white_mobile_background_logo_remove')"><i
                                                            class="icofont-close"></i></button>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="image-box-actions">
                                            <button type="button" class="btn-link" data-toggle="modal"
                                                data-target="#mediaUploadModal" id="white_mobile_background_logo_choose"
                                                onclick="setDataInsertableIds('#white_mobile_background_logo_preview,#white_mobile_background_logo_id,#white_mobile_background_logo_remove')">
                                                {{ translate('Choose image') }}
                                            </button>
                                        </div>
                                    </div>
                                    @if ($errors->has('white_mobile_background_logo'))
                                        <div class="invalid-input">{{ $errors->first('white_mobile_background_logo') }}
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="form-row mb-20">
                                <div class="col-md-4">
                                    <label class="font-14 bold black">{{ translate('Dark Logo') }}</label>
                                </div>
                                <div class="col-md-12">
                                    <input type="hidden" name="black_background_logo" id="black_background_logo_id"
                                        value="{{ isset($data['black_background_logo_id']) ? $data['black_background_logo_id'] : '' }}">
                                    <div class="image-box">
                                        <div class="d-flex flex-wrap gap-10 mb-3">
                                            @if (isset($data['black_background_logo']))
                                                <div class="preview-image-wrapper">
                                                    <img src="{{ project_asset($data['black_background_logo']) }}"
                                                        width="150" class="preview_image"
                                                        id="black_background_logo_preview" />
                                                    <button type="button" title="Remove image"
                                                        class="remove-btn style--three" id="black_background_logo_remove"
                                                        onclick="removeSelection('#black_background_logo_preview,#black_background_logo_id,#black_background_logo_remove')"><i
                                                            class="icofont-close"></i></button>
                                                </div>
                                            @else
                                                <div class="preview-image-wrapper">
                                                    <img src="{{ project_asset($placeholder_image) }}" width="150"
                                                        class="preview_image" id="black_background_logo_preview" />

                                                    <button type="button" title="Remove image"
                                                        class="remove-btn style--three d-none"
                                                        id="black_background_logo_remove"
                                                        onclick="removeSelection('#black_background_logo_preview,#black_background_logo_id,#black_background_logo_remove')"><i
                                                            class="icofont-close"></i></button>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="image-box-actions">
                                            <button type="button" class="btn-link" data-toggle="modal"
                                                data-target="#mediaUploadModal" id="black_background_logo_choose"
                                                onclick="setDataInsertableIds('#black_background_logo_preview,#black_background_logo_id,#black_background_logo_remove')">
                                                {{ translate('Choose image') }}
                                            </button>
                                        </div>
                                    </div>
                                    @if ($errors->has('black_background_logo'))
                                        <div class="invalid-input">{{ $errors->first('black_background_logo') }}</div>
                                    @endif
                                </div>
                            </div>

                            <div class="form-row mb-20">
                                <div class="col-md-4">
                                    <label class="font-14 bold black">{{ translate('Dark Logo (Mobile)') }}</label>
                                </div>
                                <div class="col-md-12">
                                    <input type="hidden" name="black_mobile_background_logo"
                                        id="black_mobile_background_logo_id"
                                        value="{{ isset($data['black_mobile_background_logo_id']) ? $data['black_mobile_background_logo_id'] : '' }}">
                                    <div class="image-box">
                                        <div class="d-flex flex-wrap gap-10 mb-3">
                                            @if (isset($data['black_mobile_background_logo']))
                                                <div class="preview-image-wrapper">
                                                    <img src="{{ project_asset($data['black_mobile_background_logo']) }}"
                                                        width="150" class="preview_image"
                                                        id="black_mobile_background_logo_preview" />
                                                    <button type="button" title="Remove image"
                                                        class="remove-btn style--three"
                                                        id="black_mobile_background_logo_remove"
                                                        onclick="removeSelection('#black_mobile_background_logo_preview,#black_mobile_background_logo_id,#black_mobile_background_logo_remove')"><i
                                                            class="icofont-close"></i></button>
                                                </div>
                                            @else
                                                <div class="preview-image-wrapper">
                                                    <img src="{{ project_asset($placeholder_image) }}" width="150"
                                                        class="preview_image" id="black_mobile_background_logo_preview" />

                                                    <button type="button" title="Remove image"
                                                        class="remove-btn style--three d-none"
                                                        id="black_mobile_background_logo_remove"
                                                        onclick="removeSelection('#black_mobile_background_logo_preview,#black_mobile_background_logo_id,#black_mobile_background_logo_remove')"><i
                                                            class="icofont-close"></i></button>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="image-box-actions">
                                            <button type="button" class="btn-link" data-toggle="modal"
                                                data-target="#mediaUploadModal" id="black_mobile_background_logo_choose"
                                                onclick="setDataInsertableIds('#black_mobile_background_logo_preview,#black_mobile_background_logo_id,#black_mobile_background_logo_remove')">
                                                {{ translate('Choose image') }}
                                            </button>
                                        </div>
                                    </div>
                                    @if ($errors->has('black_mobile_background_logo'))
                                        <div class="invalid-input">
                                            {{ $errors->first('black_mobile_background_logo') }}
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="form-row mb-20">
                                <div class="col-md-4">
                                    <label class="font-14 bold black">{{ translate('Sticky Logo') }}</label>
                                </div>
                                <div class="col-md-12">
                                    <input type="hidden" name="sticky_background_logo" id="sticky_background_logo_id"
                                        value="{{ isset($data['sticky_background_logo_id']) ? $data['sticky_background_logo_id'] : '' }}">
                                    <div class="image-box">
                                        <div class="d-flex flex-wrap gap-10 mb-3">
                                            @if (isset($data['sticky_background_logo']))
                                                <div class="preview-image-wrapper">
                                                    <img src="{{ project_asset($data['sticky_background_logo']) }}"
                                                        width="150" class="preview_image"
                                                        id="sticky_background_logo_preview" />
                                                    <button type="button" title="Remove image"
                                                        class="remove-btn style--three" id="sticky_background_logo_remove"
                                                        onclick="removeSelection('#sticky_background_logo_preview,#sticky_background_logo_id,#sticky_background_logo_remove')"><i
                                                            class="icofont-close"></i></button>
                                                </div>
                                            @else
                                                <div class="preview-image-wrapper">
                                                    <img src="{{ project_asset($placeholder_image) }}" width="150"
                                                        class="preview_image" id="sticky_background_logo_preview" />

                                                    <button type="button" title="Remove image"
                                                        class="remove-btn style--three d-none"
                                                        id="sticky_background_logo_remove d-none"
                                                        onclick="removeSelection('#sticky_background_logo_preview,#sticky_background_logo_id,#sticky_background_logo_remove')"><i
                                                            class="icofont-close"></i></button>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="image-box-actions">
                                            <button type="button" class="btn-link" data-toggle="modal"
                                                data-target="#mediaUploadModal" id="sticky_background_logo_choose"
                                                onclick="setDataInsertableIds('#sticky_background_logo_preview,#sticky_background_logo_id,#sticky_background_logo_remove')">
                                                {{ translate('Choose image') }}
                                            </button>
                                        </div>
                                    </div>
                                    @if ($errors->has('sticky_background_logo'))
                                        <div class="invalid-input">{{ $errors->first('sticky_background_logo') }}</div>
                                    @endif
                                </div>
                            </div>

                            <div class="form-row mb-20">
                                <div class="col-md-4">
                                    <label class="font-14 bold black">{{ translate('Sticky Logo (Mobile)') }}</label>
                                </div>
                                <div class="col-md-12">
                                    <input type="hidden" name="sticky_mobile_background_logo"
                                        id="sticky_mobile_background_logo_id"
                                        value="{{ isset($data['sticky_mobile_background_logo_id']) ? $data['sticky_mobile_background_logo_id'] : '' }}">
                                    <div class="image-box">
                                        <div class="d-flex flex-wrap gap-10 mb-3">
                                            @if (isset($data['sticky_mobile_background_logo']))
                                                <div class="preview-image-wrapper">
                                                    <img src="{{ project_asset($data['sticky_mobile_background_logo']) }}"
                                                        width="150" class="preview_image"
                                                        id="sticky_mobile_background_logo_preview" />
                                                    <button type="button" title="Remove image"
                                                        class="remove-btn style--three"
                                                        id="sticky_mobile_background_logo_remove"
                                                        onclick="removeSelection('#sticky_mobile_background_logo_preview,#sticky_mobile_background_logo_id,#sticky_mobile_background_logo_remove')"><i
                                                            class="icofont-close"></i></button>
                                                </div>
                                            @else
                                                <div class="preview-image-wrapper">
                                                    <img src="{{ project_asset($placeholder_image) }}" width="150"
                                                        class="preview_image"
                                                        id="sticky_mobile_background_logo_preview" />
                                                    <button type="button" title="Remove image"
                                                        class="remove-btn style--three d-none"
                                                        id="sticky_mobile_background_logo_remove"
                                                        onclick="removeSelection('#sticky_mobile_background_logo_preview,#sticky_mobile_background_logo_id,#sticky_mobile_background_logo_remove')"><i
                                                            class="icofont-close"></i></button>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="image-box-actions">
                                            <button type="button" class="btn-link" data-toggle="modal"
                                                data-target="#mediaUploadModal" id="sticky_mobile_background_logo_choose"
                                                onclick="setDataInsertableIds('#sticky_mobile_background_logo_preview,#sticky_mobile_background_logo_id,#sticky_mobile_background_logo_remove')">
                                                {{ translate('Choose image') }}
                                            </button>
                                        </div>
                                    </div>
                                    @if ($errors->has('sticky_mobile_background_logo'))
                                        <div class="invalid-input">
                                            {{ $errors->first('sticky_mobile_background_logo') }}
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="form-row mb-20">
                                <div class="col-md-4">
                                    <label class="font-14 bold black">{{ translate('Dark Sticky Logo') }}</label>
                                </div>
                                <div class="col-md-12">
                                    <input type="hidden" name="sticky_black_background_logo"
                                        id="sticky_black_background_logo_id"
                                        value="{{ isset($data['sticky_black_background_logo_id']) ? $data['sticky_black_background_logo_id'] : '' }}">
                                    <div class="image-box">
                                        <div class="d-flex flex-wrap gap-10 mb-3">
                                            @if (isset($data['sticky_black_background_logo']))
                                                <div class="preview-image-wrapper">
                                                    <img src="{{ project_asset($data['sticky_black_background_logo']) }}"
                                                        width="150" class="preview_image"
                                                        id="sticky_black_background_logo_preview" />
                                                    <button type="button" title="Remove image"
                                                        class="remove-btn style--three"
                                                        id="sticky_black_background_logo_remove"
                                                        onclick="removeSelection('#sticky_black_background_logo_preview,#sticky_black_background_logo_id,#sticky_black_background_logo_remove')"><i
                                                            class="icofont-close"></i></button>
                                                </div>
                                            @else
                                                <div class="preview-image-wrapper">
                                                    <img src="{{ project_asset($placeholder_image) }}" width="150"
                                                        class="preview_image" id="sticky_black_background_logo_preview" />

                                                    <button type="button" title="Remove image"
                                                        class="remove-btn style--three d-none"
                                                        id="sticky_black_background_logo_remove"
                                                        onclick="removeSelection('#sticky_black_background_logo_preview,#sticky_black_background_logo_id,#sticky_black_background_logo_remove')"><i
                                                            class="icofont-close"></i></button>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="image-box-actions">
                                            <button type="button" class="btn-link" data-toggle="modal"
                                                data-target="#mediaUploadModal" id="sticky_black_background_logo_choose"
                                                onclick="setDataInsertableIds('#sticky_black_background_logo_preview,#sticky_black_background_logo_id,#sticky_black_background_logo_remove')">
                                                {{ translate('Choose image') }}
                                            </button>
                                        </div>
                                    </div>
                                    @if ($errors->has('sticky_black_background_logo'))
                                        <div class="invalid-input">
                                            {{ $errors->first('sticky_black_background_logo') }}
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="form-row mb-20">
                                <div class="col-md-4">
                                    <label class="font-14 bold black">{{ translate('Dark Sticky Logo (Mobile)') }}</label>
                                </div>
                                <div class="col-md-12">
                                    <input type="hidden" name="sticky_black_mobile_background_logo"
                                        id="sticky_black_mobile_background_logo_id"
                                        value="{{ isset($data['sticky_black_mobile_background_logo_id']) ? $data['sticky_black_mobile_background_logo_id'] : '' }}">
                                    <div class="image-box">
                                        <div class="d-flex flex-wrap gap-10 mb-3">
                                            @if (isset($data['sticky_black_mobile_background_logo']))
                                                <div class="preview-image-wrapper">
                                                    <img src="{{ project_asset($data['sticky_black_mobile_background_logo']) }}"
                                                        width="150" class="preview_image"
                                                        id="sticky_black_mobile_background_logo_preview" />
                                                    <button type="button" title="Remove image"
                                                        class="remove-btn style--three"
                                                        id="sticky_black_mobile_background_logo_remove"
                                                        onclick="removeSelection('#sticky_black_mobile_background_logo_preview,#sticky_black_mobile_background_logo_id,#sticky_black_mobile_background_logo_remove')"><i
                                                            class="icofont-close"></i></button>
                                                </div>
                                            @else
                                                <div class="preview-image-wrapper">
                                                    <img src="{{ project_asset($placeholder_image) }}" width="150"
                                                        class="preview_image"
                                                        id="sticky_black_mobile_background_logo_preview" />

                                                    <button type="button" title="Remove image"
                                                        class="remove-btn style--three d-none"
                                                        id="sticky_black_mobile_background_logo_remove"
                                                        onclick="removeSelection('#sticky_black_mobile_background_logo_preview,#sticky_black_mobile_background_logo_id,#sticky_black_mobile_background_logo_remove')"><i
                                                            class="icofont-close"></i></button>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="image-box-actions">
                                            <button type="button" class="btn-link" data-toggle="modal"
                                                data-target="#mediaUploadModal"
                                                id="sticky_black_mobile_background_logo_choose"
                                                onclick="setDataInsertableIds('#sticky_black_mobile_background_logo_preview,#sticky_black_mobile_background_logo_id,#sticky_black_mobile_background_logo_remove')">
                                                {{ translate('Choose image') }}
                                            </button>
                                        </div>
                                    </div>
                                    @if ($errors->has('sticky_black_mobile_background_logo'))
                                        <div class="invalid-input">
                                            {{ $errors->first('sticky_black_mobile_background_logo') }}</div>
                                    @endif
                                </div>
                            </div>

                            <!-- Admin Logo-->
                            <div class="form-row mb-20">
                                <div class="col-md-4">
                                    <label class="font-14 bold black">{{ translate('Admin Logo') }}</label>
                                </div>
                                <div class="col-md-12">
                                    <input type="hidden" name="admin_logo" id="admin_logo_id"
                                        value="{{ isset($data['admin_logo_id']) ? $data['admin_logo_id'] : '' }}">
                                    <div class="image-box">
                                        <div class="d-flex flex-wrap gap-10 mb-3">
                                            @if (isset($data['admin_logo']))
                                                <div class="preview-image-wrapper">
                                                    <img src="{{ project_asset($data['admin_logo']) }}" width="150"
                                                        class="preview_image" id="admin_logo_preview" />
                                                    <button type="button" title="Remove image"
                                                        class="remove-btn style--three" id="admin_logo_remove"
                                                        onclick="removeSelection('#admin_logo_preview,#admin_logo_id,#admin_logo_remove')"><i
                                                            class="icofont-close"></i></button>
                                                </div>
                                            @else
                                                <div class="preview-image-wrapper">
                                                    <img src="{{ project_asset($placeholder_image) }}" width="150"
                                                        class="preview_image" id="admin_logo_preview" />

                                                    <button type="button" title="Remove image"
                                                        class="remove-btn style--three d-none" id="admin_logo_remove"
                                                        onclick="removeSelection('#admin_logo_preview,#admin_logo_id,#admin_logo_remove')"><i
                                                            class="icofont-close"></i></button>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="image-box-actions">
                                            <button type="button" class="btn-link" data-toggle="modal"
                                                data-target="#mediaUploadModal" id="admin_logo_choose"
                                                onclick="setDataInsertableIds('#admin_logo_preview,#admin_logo_id,#admin_logo_remove')">
                                                {{ translate('Choose image') }}
                                            </button>
                                        </div>
                                    </div>
                                    @if ($errors->has('admin_logo'))
                                        <div class="invalid-input">{{ $errors->first('admin_logo') }}</div>
                                    @endif
                                </div>
                            </div>

                            <div class="form-row mb-20">
                                <div class="col-md-4">
                                    <label class="font-14 bold black">{{ translate('Admin Logo (Mobile)') }}</label>
                                </div>
                                <div class="col-md-12">
                                    <input type="hidden" name="admin_mobile_logo" id="admin_mobile_logo_id"
                                        value="{{ isset($data['admin_mobile_logo_id']) ? $data['admin_mobile_logo_id'] : '' }}">
                                    <div class="image-box">
                                        <div class="d-flex flex-wrap gap-10 mb-3">
                                            @if (isset($data['admin_mobile_logo']))
                                                <div class="preview-image-wrapper">
                                                    <img src="{{ project_asset($data['admin_mobile_logo']) }}"
                                                        width="150" class="preview_image"
                                                        id="admin_mobile_logo_preview" />
                                                    <button type="button" title="Remove image"
                                                        class="remove-btn style--three" id="admin_mobile_logo_remove"
                                                        onclick="removeSelection('#admin_mobile_logo_preview,#admin_mobile_logo_id,#admin_mobile_logo_remove')"><i
                                                            class="icofont-close"></i></button>
                                                </div>
                                            @else
                                                <div class="preview-image-wrapper">
                                                    <img src="{{ project_asset($placeholder_image) }}" width="150"
                                                        class="preview_image" id="admin_mobile_logo_preview" />

                                                    <button type="button" title="Remove image"
                                                        class="remove-btn style--three d-none"
                                                        id="admin_mobile_logo_remove"
                                                        onclick="removeSelection('#admin_mobile_logo_preview,#admin_mobile_logo_id,#admin_mobile_logo_remove')"><i
                                                            class="icofont-close"></i></button>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="image-box-actions">
                                            <button type="button" class="btn-link" data-toggle="modal"
                                                data-target="#mediaUploadModal" id="admin_mobile_logo_choose"
                                                onclick="setDataInsertableIds('#admin_mobile_logo_preview,#admin_mobile_logo_id,#admin_mobile_logo_remove')">
                                                {{ translate('Choose image') }}
                                            </button>
                                        </div>
                                    </div>
                                    @if ($errors->has('admin_mobile_logo'))
                                        <div class="invalid-input">{{ $errors->first('admin_mobile_logo') }}</div>
                                    @endif
                                </div>
                            </div>

                            <div class="form-row mb-20">
                                <div class="col-md-4">
                                    <label class="font-14 bold black">{{ translate('Admin Dark Logo') }}</label>
                                </div>
                                <div class="col-md-12">
                                    <input type="hidden" name="admin_dark_logo" id="admin_dark_logo_id"
                                        value="{{ isset($data['admin_dark_logo_id']) ? $data['admin_dark_logo_id'] : '' }}">
                                    <div class="image-box">
                                        <div class="d-flex flex-wrap gap-10 mb-3">
                                            @if (isset($data['admin_dark_logo']))
                                                <div class="preview-image-wrapper">
                                                    <img src="{{ project_asset($data['admin_dark_logo']) }}"
                                                        width="150" class="preview_image"
                                                        id="admin_dark_logo_preview" />
                                                    <button type="button" title="Remove image"
                                                        class="remove-btn style--three" id="admin_dark_logo_remove"
                                                        onclick="removeSelection('#admin_dark_logo_preview,#admin_dark_logo_id,#admin_dark_logo_remove')"><i
                                                            class="icofont-close"></i></button>
                                                </div>
                                            @else
                                                <div class="preview-image-wrapper">
                                                    <img src="{{ project_asset($placeholder_image) }}" width="150"
                                                        class="preview_image" id="admin_dark_logo_preview" />

                                                    <button type="button" title="Remove image"
                                                        class="remove-btn style--three d-none" id="admin_dark_logo_remove"
                                                        onclick="removeSelection('#admin_dark_logo_preview,#admin_dark_logo_id,#admin_dark_logo_remove')"><i
                                                            class="icofont-close"></i></button>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="image-box-actions">
                                            <button type="button" class="btn-link" data-toggle="modal"
                                                data-target="#mediaUploadModal" id="admin_dark_logo_choose"
                                                onclick="setDataInsertableIds('#admin_dark_logo_preview,#admin_dark_logo_id,#admin_dark_logo_remove')">
                                                {{ translate('Choose image') }}
                                            </button>
                                        </div>
                                    </div>
                                    @if ($errors->has('admin_dark_logo'))
                                        <div class="invalid-input">{{ $errors->first('admin_dark_logo') }}</div>
                                    @endif
                                </div>
                            </div>

                            <div class="form-row mb-20">
                                <div class="col-md-4">
                                    <label class="font-14 bold black">{{ translate('Admin Dark Logo (Mobile)') }}</label>
                                </div>
                                <div class="col-md-12">
                                    <input type="hidden" name="admin_dark_mobile_logo" id="admin_dark_mobile_logo_id"
                                        value="{{ isset($data['admin_dark_mobile_logo_id']) ? $data['admin_dark_mobile_logo_id'] : '' }}">
                                    <div class="image-box">
                                        <div class="d-flex flex-wrap gap-10 mb-3">
                                            @if (isset($data['admin_dark_mobile_logo']))
                                                <div class="preview-image-wrapper">
                                                    <img src="{{ project_asset($data['admin_dark_mobile_logo']) }}"
                                                        width="150" class="preview_image"
                                                        id="admin_dark_mobile_logo_preview" />
                                                    <button type="button" title="Remove image"
                                                        class="remove-btn style--three" id="admin_dark_mobile_logo_remove"
                                                        onclick="removeSelection('#admin_dark_mobile_logo_preview,#admin_dark_mobile_logo_id,#admin_dark_mobile_logo_remove')"><i
                                                            class="icofont-close"></i></button>
                                                </div>
                                            @else
                                                <div class="preview-image-wrapper">
                                                    <img src="{{ project_asset($placeholder_image) }}" width="150"
                                                        class="preview_image" id="admin_dark_mobile_logo_preview" />

                                                    <button type="button" title="Remove image"
                                                        class="remove-btn style--three d-none"
                                                        id="admin_dark_mobile_logo_remove d-none"
                                                        onclick="removeSelection('#admin_dark_mobile_logo_preview,#admin_dark_mobile_logo_id,#admin_dark_mobile_logo_remove')"><i
                                                            class="icofont-close"></i></button>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="image-box-actions">
                                            <button type="button" class="btn-link" data-toggle="modal"
                                                data-target="#mediaUploadModal" id="admin_dark_mobile_logo_choose"
                                                onclick="setDataInsertableIds('#admin_dark_mobile_logo_preview,#admin_dark_mobile_logo_id,#admin_dark_mobile_logo_remove')">
                                                {{ translate('Choose image') }}
                                            </button>
                                        </div>
                                    </div>
                                    @if ($errors->has('admin_dark_mobile_logo'))
                                        <div class="invalid-input">{{ $errors->first('admin_dark_mobile_logo') }}</div>
                                    @endif
                                </div>
                            </div>

                            <div class="form-row mb-20">
                                <div class="col-md-12">
                                    <label class="font-14 bold black">{{ translate('Login Page Background Image') }}</label>
                                </div>
                                <div class="col-md-12">
                                    <input type="hidden" name="login_bg_image" id="login_bg_image_id"
                                        value="{{ isset($data['login_bg_image_id']) ? $data['login_bg_image_id'] : '' }}">
                                    <div class="image-box">
                                        <div class="d-flex flex-wrap gap-10 mb-3">
                                            @if (isset($data['login_bg_image']))
                                                <div class="preview-image-wrapper">
                                                    <img src="{{ project_asset($data['login_bg_image']) }}"
                                                        width="150" class="preview_image"
                                                        id="login_bg_image_preview" />
                                                    <button type="button" title="Remove image"
                                                        class="remove-btn style--three" id="login_bg_image_remove"
                                                        onclick="removeSelection('#login_bg_image_preview,#login_bg_image_id,#login_bg_image_remove')"><i
                                                            class="icofont-close"></i></button>
                                                </div>
                                            @else
                                                <div class="preview-image-wrapper">
                                                    <img src="{{ project_asset($placeholder_image) }}" width="150"
                                                        class="preview_image" id="login_bg_image_preview" />

                                                    <button type="button" title="Remove image"
                                                        class="remove-btn style--three d-none"
                                                        id="login_bg_image_remove d-none"
                                                        onclick="removeSelection('#login_bg_image_preview,#login_bg_image_id,#login_bg_image_remove')"><i
                                                            class="icofont-close"></i></button>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="image-box-actions">
                                            <button type="button" class="btn-link" data-toggle="modal"
                                                data-target="#mediaUploadModal" id="login_bg_image_choose"
                                                onclick="setDataInsertableIds('#login_bg_image_preview,#login_bg_image_id,#login_bg_image_remove')">
                                                {{ translate('Choose image') }}
                                            </button>
                                        </div>
                                    </div>
                                    @if ($errors->has('login_bg_image'))
                                        <div class="invalid-input">{{ $errors->first('login_bg_image') }}</div>
                                    @endif
                                </div>
                            </div>
                            <!-- /Admin Logo-->

                            <div class="form-row mb-20">
                                <div class="col-md-4">
                                    <label class="font-14 bold black">{{ translate('Favicon') }}</label>
                                </div>
                                <div class="col-md-12">
                                    <input type="hidden" name="favicon" id="favicon_id"
                                        value="{{ isset($data['favicon_id']) ? $data['favicon_id'] : '' }}">
                                    <div class="image-box">
                                        <div class="d-flex flex-wrap gap-10 mb-3">
                                            @if (isset($data['favicon']))
                                                <div class="preview-image-wrapper">
                                                    <img src="{{ project_asset($data['favicon']) }}" width="150"
                                                        class="preview_image" id="favicon_preview" />
                                                    <button type="button" title="Remove image"
                                                        class="remove-btn style--three" id="favicon_remove"
                                                        onclick="removeSelection('#favicon_preview,#favicon_id,#favicon_remove')"><i
                                                            class="icofont-close"></i></button>
                                                </div>
                                            @else
                                                <div class="preview-image-wrapper">
                                                    <img src="{{ project_asset($placeholder_image) }}" width="150"
                                                        class="preview_image" id="favicon_preview" />

                                                    <button type="button" title="Remove image"
                                                        class="remove-btn style--three d-none" id="favicon_remove"
                                                        onclick="removeSelection('#favicon_preview,#favicon_id,#favicon_remove')"><i
                                                            class="icofont-close"></i></button>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="image-box-actions">
                                            <button type="button" class="btn-link" data-toggle="modal"
                                                data-target="#mediaUploadModal" id="favicon_choose"
                                                onclick="setDataInsertableIds('#favicon_preview,#favicon_id,#favicon_remove')">
                                                {{ translate('Choose image') }}
                                            </button>
                                        </div>
                                    </div>
                                    @if ($errors->has('favicon'))
                                        <div class="invalid-input">{{ $errors->first('favicon') }}</div>
                                    @endif
                                </div>
                            </div>

                            <div class="form-row mb-20">
                                <div class="col-md-4">
                                    <label class="font-14 bold black">{{ translate('Default Language') }}</label>
                                </div>
                                <div class="col-md-12">
                                    <select class="default-language form-control" name="default_language"
                                        id="default_language" placeholder="{{ translate('Select default language') }}">
                                        @foreach ($active_langs as $lang)
                                            @if ($lang->status == config('settings.general_status.active'))
                                                <option value="{{ $lang->id }}"
                                                    {{ isset($data['default_language']) && $data['default_language'] == $lang->id ? 'selected' : '' }}>
                                                    {{ $lang->name }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                    @if ($errors->has('default_language'))
                                        <div class="invalid-input">{{ $errors->first('default_language') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-row mb-20">
                                <div class="col-md-4">
                                    <label class="font-14 bold black">{{ translate('Select Default Timezone') }}</label>
                                </div>
                                <div class="col-md-12">
                                    <select class="default-timezone form-control" name="default_timezone"
                                        id="default_timezone" placeholder="{{ translate('Select Default Timezone') }}">
                                        @foreach ($tzlist as $tz)
                                            <option value="{{ $tz }}"
                                                {{ isset($data['default_timezone']) && $data['default_timezone'] == $tz ? 'selected' : '' }}>
                                                {{ $tz }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('default_timezone'))
                                        <div class="invalid-input">{{ $errors->first('default_timezone') }}</div>
                                    @endif
                                </div>
                            </div>

                            <div class="form-row mb-20">
                                <div class="col-md-4">
                                    <label class="font-14 bold black">{{ translate('Copyright Text') }}</label>
                                </div>
                                <div class="col-md-12">
                                    <div class="editor-wrap">
                                        <textarea name="copyright_text" id="copyright_text">{{ isset($data['copyright_text']) ? $data['copyright_text'] : '' }}</textarea>
                                    </div>
                                    @if ($errors->has('copyright_text'))
                                        <div class="invalid-input">{{ $errors->first('copyright_text') }}</div>
                                    @endif
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-md-12 general-settings-submit">
                                    <button type="submit" class="btn long btn-orange">{{ translate('Submit') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @include('core::base.media.partial.media_modal')
    </div>
    <!-- /General settings form -->
@endsection
@section('custom_scripts')
    <script src="{{ asset('backend/assets/plugins/select2/select2.min.js') }}"></script>
    <!--Editor-->
    <script src="{{ asset('backend/assets/plugins/summernote/summernote-lite.js') }}"></script>
    <!-- <script src="{{ asset('/public/backend/assets/plugins/summernote/summernote-lite.js') }}"></script> -->
    <!--End Editor-->
    <script type="application/javascript">
    (function($) {
        "use strict";
        initDropzone()
        $(document).ready(function() {
            is_for_browse_file = true
            filtermedia()
            var isRtl = $('body').hasClass('admin-rtl') || $('body').hasClass('admin-rtl_dark');
            /*Select default language*/
            $('.default-language').select2({
                theme: "classic",
                dir: isRtl ? 'rtl' : 'ltr',
            });
            /*Select default timezone*/
            $('.default-timezone').select2({
                theme: "classic",
                dir: isRtl ? 'rtl' : 'ltr',
            });
            /*Select default currency*/
            $('.default-currency').select2({
                theme: "classic",
                dir: isRtl ? 'rtl' : 'ltr',
            });
            /*Select currency position*/
            $('.currency-position').select2({
                theme: "classic",
                dir: isRtl ? 'rtl' : 'ltr',
            });

            $('#copyright_text').summernote(getSummernoteContentEditorOptions({
                placeholder: 'Copyright text',
                direction: isRtl ? 'rtl' : 'ltr',
                toolbar: [
                    ["style", ["style"]],
                    ["font", ["bold", "underline", "clear"]],
                    ["color", ["color"]],
                    ["para", ["ul", "ol", "paragraph"]],
                    ["table", ["table"]],
                    ["insert", ["link", "video"]],
                    ["view", ["fullscreen", "codeview", "help"]],
                ]
            }));
        })
    })(jQuery);
</script>
@endsection