@extends('core::base.layouts.master')
@section('title')
    {{ translate('Edit State') }}
@endsection
@section('custom_css')
    <link rel="stylesheet" href="{{ asset('backend/assets/plugins/select2/select2.min.css') }}">
@endsection
@section('main_content')
    <div class="row">
        <div class="col-lg-6 mx-auto">
            <div class="form-element py-30 mb-30" style="border-radius: 12px !important; overflow: hidden !important;">
                <h4 class="font-20 mb-30">{{ translate('Edit State') }}</h4>
                <form action="{{ route('plugin.tlcommercecore.shipping.locations.states.update') }}" method="POST">
                    @csrf
                    <div class="form-row mb-20">
                        <div class="col-sm-4">
                            <label class="font-14 bold black">{{ translate('Name') }} </label>
                        </div>
                        <div class="col-sm-8">
                            <input type="text" name="name" class="theme-input-style"
                                value="{{ $stateDetails->translation('name', getDefaultLang()) }}"
                                placeholder="{{ translate('Type Name') }}">
                            <input type="hidden" name="id" value="{{ $stateDetails->id }}">
                            <input type="hidden" name="lang" value="{{ getDefaultLang() }}">
                            @if ($errors->has('name'))
                                <div class="invalid-input">{{ $errors->first('name') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="form-row mb-20">
                        <div class="col-sm-4">
                            <label class="font-14 bold black">{{ translate('Code') }}</label>
                        </div>
                        <div class="col-sm-8">
                            <input type="text" name="code" class="theme-input-style"
                                value="{{ $stateDetails->code }}" placeholder="{{ translate('Type  Here') }}">
                            @if ($errors->has('code'))
                                <div class="invalid-input">{{ $errors->first('code') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="form-row mb-20">
                        <div class="col-sm-4">
                            <label class="font-14 bold black">{{ translate('Country') }}</label>
                        </div>
                        <div class="col-sm-8">
                            <select class="countrySelect form-control" name="country"
                                placeholder="{{ translate('Select a option') }}">
                                @foreach ($countries as $country)
                                    <option value="{{ $country->id }}"
                                        {{ $stateDetails->country_id == $country->id ? 'selected' : '' }}>
                                        {{ $country->name }}
                                    </option>
                                @endforeach
                            </select>
                            @if ($errors->has('country'))
                                <div class="invalid-input">{{ $errors->first('country') }}</div>
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
@endsection
@section('custom_scripts')
    <script src="{{ asset('backend/assets/plugins/select2/select2.min.js') }}"></script>
    <script>
        (function($) {
            "use strict";
            $(document).ready(function() {
                $('.countrySelect').select2({
                    theme: "classic",
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

</style>
