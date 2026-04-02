@php
    $roles = getAllRoleForAssign();
    
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
    {{ translate('Update User') }}
@endsection
@section('custom_css')
    <link rel="stylesheet" href="{{ asset('backend/assets/plugins/select2/select2.min.css') }}">

    <link rel="stylesheet" href="{{ asset('backend/assets/plugins/daterangepicker/daterangepicker.css') }}">
@endsection
@section('main_content')
    <div class="row justify-content-center align-items-center">
        <div class="col-md-7">
            <!-- User profile-->
            <div class="card p-4" style="border-radius: 12px !important; overflow: hidden !important;">
                <div class="card-body" style="margin-bottom: 0px !important; padding-bottom: 0px !important;">
                    <!-- <div class="post-head d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex align-items-center">
                            <div class="content">
                                <h4 class="mb-1">{{ translate('Update Profile') }}</h4>
                            </div>
                        </div>
                    </div> -->

                    <div>
                        <form action="{{ route('core.update.profile') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="id" value="{{ $user->id }}">
                            <input type="hidden" name="is_for_profile" value="true">


                            <div class="form-row mb-20">
                                <!-- <div class="col-md-4">
                                    <label class="font-14 bold black">{{ translate('Profile Picture') }}</label>
                                </div> -->
                                <div class="col-md-12">
                                    <input type="hidden" name="pro_pic" id="pro_pic_id" value="{{ $user->pro_pic_id }}">
                                    <div class="image-box">
                                        <div class="d-flex flex-wrap gap-10 mb-3">
                                    <div class="preview-image-wrapper rounded-circle" 
                                        style="cursor: pointer; position: relative; border: none;" 
                                        data-toggle="modal" 
                                        data-target="#mediaUploadModal" 
                                        onclick="setDataInsertableIds('#pro_pic_preview, #pro_pic_id, #pro_pic_remove')">
                                        
                                        @if ($user->pro_pic)
                                            <img src="{{ project_asset($user->pro_pic) }}"
                                                alt="{{ $user->pro_pic_alt }}" 
                                                width="150" height="150"
                                                class="preview_image rounded-circle"
                                                id="pro_pic_preview" 
                                                style="border: 1px solid #ff8c00; object-fit: cover; display: block;" />
                                        @else
                                            <img src="{{ project_asset($placeholder_image) }}" 
                                                width="150" height="150"
                                                class="preview_image rounded-circle" 
                                                id="pro_pic_preview"
                                                style="border: 1px solid #ff8c00; object-fit: cover; display: block;" />
                                        @endif

                                        <div style="
                                            position: absolute;
                                            bottom: 5px;
                                            right: 5px;
                                            background: #ff5A1f;
                                            color: white;
                                            border-radius: 50%;
                                            width: 30px;
                                            height: 30px;
                                            display: flex;
                                            align-items: center;
                                            justify-content: center;
                                            border: none;
                                        ">
                                            <x-lucide-camera style="width: 16px; height: 16px;" />
                                        </div>
                                    </div>

                                    <input type="hidden" name="pro_pic" id="pro_pic_id" value="{{ $user->pro_pic }}">
                                    <button type="button" class="d-none" id="pro_pic_remove"></button>
                                </div>
                                        <!-- <div class="d-flex flex-wrap gap-10 mb-3">
                                            @if ($user->pro_pic)
                                                <div class="preview-image-wrapper rounded-circle" style="border: none;">
                                                    <img src="{{ project_asset($user->pro_pic) }}"
                                                        alt="{{ $user->pro_pic_alt }}" width="150" class="preview_image rounded-circle"
                                                        id="pro_pic_preview" style="border: 1px solid #ff8c00;"  />
                                                </div>
                                            @else
                                                <div class="preview-image-wrapper rounded-circle" style="border: none;">
                                                    <img src="{{ project_asset($placeholder_image) }}" width="150"
                                                        class="preview_image rounded-circle" id="pro_pic_preview"
                                                        style="border: 1px solid #ff8c00;" />
                                                </div>
                                            @endif
                                        </div>
                                        <div class="image-box-actions">
                                            <button type="button" class="btn-link" data-toggle="modal"
                                                data-target="#mediaUploadModal" id="pro_pic_choose"
                                                onclick="setDataInsertableIds('#pro_pic_preview,#pro_pic_id,#pro_pic_remove')">
                                                {{ translate('Choose image') }}
                                            </button>
                                        </div> -->
                                    </div>
                                </div>
                            </div>

                            <div class="form-row mb-10">
                                <div class="col-md-12">
                                    <label class="bold black" style="font-size: 18px;">{{ translate('Personal Information') }}</label>
                                </div>
                            </div>

                            <div class="form-row mb-10">
                                <div class="col-md-12">
                                    <label class="font-14 bold black">{{ translate('Name') }}</label>
                                </div>
                                <div class="col-md-12">
                                    <input type="text" name="name" class="theme-input-style"
                                        value="{{ $user->name }}" placeholder="{{ translate('Give your name') }}">
                                    @if ($errors->has('name'))
                                        <div class="invalid-input">{{ $errors->first('name') }}</div>
                                    @endif
                                </div>
                            </div>

                            <div class="form-row mb-10">
                                <div class="col-md-12">
                                    <label class="font-14 bold black">{{ translate('Email') }}</label>
                                </div>
                                <div class="col-md-12">
                                    <input type="email" name="email" class="theme-input-style"
                                        value="{{ $user->email }}"
                                        placeholder="{{ translate('Give your email address') }}">
                                    @if ($errors->has('email'))
                                        <div class="invalid-input">{{ $errors->first('email') }}</div>
                                    @endif
                                </div>
                            </div>

                            <div class="form-row mb-10">
                                <div class="col-md-12">
                                    <label class="font-14 bold black">{{ translate('Old Password') }}</label>
                                </div>
                                <div class="col-md-12">
                                    <input type="password" name="old_password" class="theme-input-style"
                                        placeholder="{{ translate('Old password') }}">
                                    @if ($errors->has('old_password'))
                                        <div class="invalid-input">{{ $errors->first('old_password') }}</div>
                                    @endif
                                </div>
                            </div>

                            <div class="form-row mb-10">
                                <div class="col-md-12">
                                    <label class="font-14 bold black">{{ translate('Password') }}</label>
                                </div>
                                <div class="col-md-12">
                                    <input type="password" name="password" class="theme-input-style"
                                        placeholder="{{ translate('Give your password') }}">
                                    @if ($errors->has('password'))
                                        <div class="invalid-input">{{ $errors->first('password') }}</div>
                                    @endif
                                </div>
                            </div>

                            <div class="form-row mb-30">
                                <div class="col-md-12">
                                    <label class="font-14 bold black">{{ translate('Confirm Password') }}</label>
                                </div>
                                <div class="col-md-12">
                                    <input type="password" name="password_confirmation" class="theme-input-style"
                                        placeholder="{{ translate('Confirm your password') }}">
                                    @if ($errors->has('password_confirmation'))
                                        <div class="invalid-input">{{ $errors->first('password_confirmation') }}</div>
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
            <!-- /User profile-->

            @include('core::base.media.partial.media_modal')

        </div>
    </div>
@endsection
@section('custom_scripts')
    <script>
        (function($) {
            "use strict";
            initDropzone()
            $(document).ready(function() {
                $('#mediaUploadModal').on('shown.bs.modal', function() {
                    $(document).off('focusin.modal');
                });

                is_for_browse_file = true
                enable_multiple_file_select = true
                filtermedia()
            })
        })(jQuery);
    </script>
@endsection


<style>

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
        background: #e07b00 !important;
        border-color: #e07b00 !important;
        box-shadow: none !important;
        outline: none !important;
    }

</style>