@extends('core::base.layouts.master')
@section('title')
    {{ translate('Edit Country') }}
@endsection
@section('custom_css')
    <link rel="stylesheet" href="{{ asset('backend/assets/plugins/select2/select2.min.css') }}">
@endsection
@section('main_content')
    <div class="row">
        <div class="col-lg-6 mx-auto">
            <div class="card mb-30" style="border-radius: 12px !important; overflow: hidden !important;">
                <div class="card-header bg-white border-bottom2">
                    <h4>{{ translate('Edit Country') }}</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('plugin.tlcommercecore.shipping.locations.country.update') }}" method="POST">
                        @csrf
                        <div class="form-row mb-20">
                            <div class="col-sm-4">
                                <label class="font-14 bold black">{{ translate('Name') }} </label>
                            </div>
                            <div class="col-sm-8">
                                <input type="hidden" name="id" value="{{ $countryDetails->id }}">
                                <input type="hidden" name="lang" value="{{ getDefaultLang() }}">
                                <input type="text" name="name" class="theme-input-style"
                                    value="{{ $countryDetails->translation('name', getDefaultLang()) }}"
                                    placeholder="{{ translate('Enter Name') }}">
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
                                    value="{{ $countryDetails->code }}" placeholder="{{ translate('Enter Code') }}">
                                @if ($errors->has('code'))
                                    <div class="invalid-input">{{ $errors->first('code') }}</div>
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
@endsection
@section('custom_scripts')
    <script src="{{ asset('backend/assets/plugins/select2/select2.min.js') }}"></script>
    <script>
        (function($) {
            "use strict";
            $(document).ready(function() {
                $('.countryCodeSelect').select2({
                    theme: "classic",
                    templateResult: formatState,
                    templateSelection: formatState,
                });
            });

            function formatState(opt) {
                var base_path = "{{ url('/') }}";
                if (!opt.id) {
                    return opt.text.toUpperCase();
                }
                var image = $(opt.element).attr('data-image');
                var optimage = base_path + '/public/flags/' + image + '.png';
                if (!optimage) {
                    return opt.text.toUpperCase();
                } else {
                    var $opt = $(
                        '<span><img src="' + optimage + '" width="20px" class="mr-2" /> ' + opt.text
                        .toUpperCase() + '</span>'
                    );
                    return $opt;
                }
            };
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
