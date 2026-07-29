@extends('core::base.layouts.master')
@section('title')
    {{ translate('Edit Collection') }}
@endsection

@section('main_content')
    <div class="row">
        <div class="col-lg-6 mx-auto">
            <div class="mb-3">
                <p class="alert alert-info">You are editing <strong>"{{ getLanguageNameByCode($lang) }}"</strong> version
                </p>
            </div>
            <div class="card mb-30" style="border-radius: 12px !important; overflow: hidden !important;">
                <div class="card-header bg-white border-bottom2 py-3">
                    <h4 class="" style="font-size: 30px;">{{ translate('Edit Collection') }}</h4>
                </div>
                <div class="card-body">
                    <!--Language Switcher-->
                    <ul class="nav nav-tabs nav-fill border-light border-0 mb-20">
                        @foreach ($languages as $key => $language)
                            <li class="nav-item">
                                <a class="nav-link @if ($language->code == $lang) active border-0 @else bg-light @endif py-3"
                                    href="{{ route('plugin.tlcommercecore.product.collection.edit', ['id' => $collection_details->id, 'lang' => $language->code]) }}">
                                    <img src="{{ asset('/public/flags/') . '/' . $language->code . '.png' }}"
                                        width="20px">
                                    <span>{{ $language->name }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                    <!--End Language Switcher--->
                    <form action="{{ route('plugin.tlcommercecore.product.collection.update') }}" method="POST">
                        @csrf
                        <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold black">{{ translate('Name') }} </label>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" name="name"
                                    class="theme-input-style @if (request()->has('lang') && request()->get('lang') == getdefaultlang()) collection_name @endif "
                                    value="{{ $collection_details->translation('name', $lang) }}"
                                    placeholder="{{ translate('Type Name') }}">
                                <input type="hidden" name="permalink" id="permalink_input_field"
                                    value="{{ $collection_details->permalink }}">
                                <input type="hidden" name="id" value="{{ $collection_details->id }}">
                                <input type="hidden" name="lang" value="{{ $lang }}">
                                @if ($errors->has('name'))
                                    <div class="invalid-input">{{ $errors->first('name') }}</div>
                                @endif
                            </div>
                        </div>
                        <!---Permalink---->

                        <div
                            class="form-row mb-20 permalink-input-group @if (!empty($lang) && $lang != getdefaultlang()) area-disabled @endif">
                            <div class="col-sm-4">
                                <label class="font-14 bold black">{{ translate('Permalink') }} </label>
                            </div>
                            <div class="col-sm-8">
                                <a href="#">{{ url('') }}/<span
                                        id="permalink">{{ $collection_details->permalink }}</span><span
                                        class="btn custom-btn ml-1 permalink-edit-btn">{{ translate('Edit') }}</span></a>
                                @if ($errors->has('permalink'))
                                    <div class="invalid-input">{{ $errors->first('permalink') }}</div>
                                @endif
                                <div class="permalink-editor d-none">
                                    <input type="text" class="theme-input-style" id="permalink-updated-input"
                                        placeholder="{{ translate('Type here') }}">
                                    <button type="button" class="btn long mt-2 btn-danger permalink-cancel-btn"
                                        data-dismiss="modal">{{ translate('Cancel') }}</button>
                                    <button type="button"
                                        class="btn long mt-2 btn-orange permalink-save-btn">{{ translate('Save') }}</button>
                                </div>
                            </div>
                        </div>
                        <!---End Permalink---->
                        <div class="form-row mb-20 @if (!empty($lang) && $lang != getdefaultlang()) area-disabled @endif">
                            <div class="col-sm-4">
                                <label class="font-14 bold black">{{ translate('Image') }}</label>
                            </div>
                            <div class="col-sm-8">
                                @include('core::base.includes.media.media_input', [
                                    'input' => 'image',
                                    'data' => $collection_details->image,
                                ])
                                @if ($errors->has('image'))
                                    <div class="invalid-input">{{ $errors->first('image') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-12 text-right">
                                <button type="submit" class="btn long btn-orange">{{ translate('Update') }}</button>
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
            $('.collection_name').change(function(e) {
                e.preventDefault();
                let name = $('.collection_name').val();
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

</style>
