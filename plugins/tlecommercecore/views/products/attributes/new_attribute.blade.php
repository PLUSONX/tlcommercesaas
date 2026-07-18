@extends('core::base.layouts.master')
@section('title')
    {{ translate('New Attribute') }}
@endsection
@section('custom_css')
@endsection
@section('main_content')
    <div class="row">
        <div class="col-lg-7 mx-auto">
            <div class="mb-3">
                <p class="alert alert-info">You are inserting <strong>"{{ getLanguageNameByCode(getDefaultLang()) }}"</strong> version</p>
            </div>
            <div class="form-element py-30 mb-30">
                <h4 class="font-20 mb-30">{{ translate('New Attribute') }}</h4>
                <form action="{{ route('plugin.tlcommercecore.product.attributes.store') }}" method="POST">
                    @csrf
                    <div class="form-row mb-20">
                        <div class="col-sm-4">
                            <label class="font-14 bold black">{{ translate('Name') }} </label>
                        </div>
                        <div class="col-sm-8">
                            <input type="text" name="name" class="theme-input-style " value="{{ old('name') }}"
                                placeholder="{{ translate('Type here') }}">
                            @if ($errors->has('name'))
                                <div class="invalid-input">{{ $errors->first('name') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="form-row mb-20">
                        <div class="col-sm-4">
                            <label class="font-14 bold black">{{ translate('Multi Select') }}</label>
                        </div>
                        <div class="col-sm-8">
                            <input type="hidden" name="multi_select" value="0">
                            <label class="switch glow primary medium">
                                <input type="checkbox" name="multi_select" id="multi_select" value="1"
                                    {{ old('multi_select') ? 'checked' : '' }}>
                                <span class="control"></span>
                            </label>
                            @if ($errors->has('multi_select'))
                                <div class="invalid-input">{{ $errors->first('multi_select') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="form-row mb-20">
                        <div class="col-sm-4">
                            <label class="font-14 bold black">{{ translate('Multi Select Limit') }}</label>
                        </div>
                        <div class="col-sm-8">
                            <input type="number" name="multi_select_limit" id="multi_select_limit"
                                class="theme-input-style" min="1" step="1"
                                value="{{ old('multi_select_limit') }}"
                                placeholder="{{ translate('Type here') }}"
                                {{ old('multi_select') ? '' : 'disabled' }}>
                            @if ($errors->has('multi_select_limit'))
                                <div class="invalid-input">{{ $errors->first('multi_select_limit') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-12 text-right">
                            <button type="submit" class="btn long">{{ translate('Save') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('custom_scripts')
    <script>
        (function($) {
            "use strict";

            function toggleMultiSelectLimit() {
                let enabled = $('#multi_select').is(':checked');
                let $limit = $('#multi_select_limit');
                $limit.prop('disabled', !enabled);
                if (!enabled) {
                    $limit.val('');
                }
            }

            $('#multi_select').on('change', toggleMultiSelectLimit);
            toggleMultiSelectLimit();
        })(jQuery);
    </script>
@endsection
