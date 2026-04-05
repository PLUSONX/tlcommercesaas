@php
    $facebook = $integrationSettings['facebook_pixel'] ?? null;
    $facebookSettings = $facebook ? json_decode($facebook->settings, true) : [];
@endphp
@extends('core::base.layouts.master')
@section('title')
    {{ translate('Social Media Integration') }}
@endsection
@section('main_content')

<div class="row">
        <div class="col-12">
            <div class="card bg-transparent mb-20">

                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="" style="font-size: 30px;">{{ translate('Social Media Integration') }}</h4>
                    </div>
                </div>

            </div>
        </div>
</div>

<div class="card mb-4" style="border-radius: 12px !important; overflow: hidden !important;">
    <form action="{{ route('plugin.tlcommercecore.marketing.social.media.integration.update') }}" method="POST">
        @csrf
        
        <div class="card-header bg-transparent py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Facebook Pixel</h5>

            <div class="form-check form-switch custom-switch-container d-flex align-items-center m-0 p-0">
                <label class="form-check-label fw-medium me-2" for="facebookPixelToggle" 
                    style="cursor: pointer; font-size: 0.85rem; color: #6c757d; margin-left: 30px;">
                    {{ $facebook && $facebook->is_active ? 'Active' : 'Inactive' }}
                </label>
                
                <div class="switch-wrapper ml-1">
                    <input class="form-check-input" 
                        type="checkbox" 
                        role="switch" 
                        id="facebookPixelToggle" 
                        name="facebook_pixel[is_active]"
                        value="1"
                        {{ $facebook && $facebook->is_active ? 'checked' : '' }}>
                    <span class="checkmark"></span>
                </div>
            </div>
            

            <!-- <div class="form-check form-switch d-flex align-items-center m-0 p-0">
                <label class="form-check-label fw-medium me-2" for="facebookPixelToggle" style="cursor: pointer; font-size: 0.85rem; color: #6c757d; margin-left: 30px;">
                    {{ $facebook && $facebook->is_active ? 'Active' : 'Inactive' }}
                </label>
                <input class="form-check-input" 
                       type="checkbox" 
                       role="switch" 
                       id="facebookPixelToggle" 
                       name="facebook_pixel[is_active]"
                       value="1"
                       style="width: 2.4em; height: 1.2em; cursor: pointer; margin: 0;"
                       {{ $facebook && $facebook->is_active ? 'checked' : '' }}>
            </div> -->
        </div>

        <div class="card-body">
            <div class="form-group">
                <label class="form-label fw-bold small text-uppercase text-muted">Pixel ID</label>
                <input type="text"
                       name="facebook_pixel[pixel_id]"
                       class="form-control"
                       value="{{ $facebookSettings['pixel_id'] ?? '' }}"
                       placeholder="e.g. 123456789012345">
            </div>
        </div>

        <div class="card-footer bg-transparent border-top-0 pb-3">
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn long btn-orange">{{ translate('Update Settings') }}</button>
            </div>
        </div>
    </form>
</div>


@endsection
@section('custom_scripts')

<script>

   
</script>

@endsection

<!-- @section('custom_css') -->

<style>

    .switch-wrapper input.form-check-input {
    appearance: none;
    -webkit-appearance: none;
    position: absolute;
    opacity: 0;
    width: 18px;
    height: 18px;
    margin: 0;
    padding: 0;
    cursor: pointer;
    z-index: 1;
}

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


    /* 1. Hide the native browser checkbox */
    .product-id, .select-all {
        position: absolute;
        opacity: 0;
        cursor: pointer;
        height: 0;
        width: 0;
    }

    /* 2. Style the custom checkmark for both TH and TD */
    .checkmark {
        display: inline-block;
        height: 18px;
        width: 18px;
        background-color: transparent;
        border: 2px solid #ff8c00; /* Orange border */
        border-radius: 3px;
        position: relative;
        cursor: pointer;
    }

    /* 3. Style when the checkbox is checked */
    input:checked ~ .checkmark {
        background-color: #ff5A1f; /* Fill with orange */
    }

    /* 4. The actual check symbol (the white "L" shape) */
    .checkmark:after {
        content: "";
        position: absolute;
        display: none;
        left: 4px;
        top: 0px;
        width: 7px;
        height: 12px;
        border: solid white;
        border-width: 0 2px 2px 0;
        transform: rotate(45deg);
    }

    /* Show the check symbol when checked */
    input:checked ~ .checkmark:after {
        display: block;
    }

    /* 1. The background of the switch track when ON */
    .switch.primary input:checked ~ .control {
        background-color: #ff5A1f !important;
        border-color: #ff8c00 !important;
    }

    /* 2. The sliding circle (the knob) */
    /* We usually keep this white or a very light grey for contrast */
    .switch.primary .control:after {
        background-color: #ffffff !important;
        border: 1px solid #e0e0e0;
        box-shadow: none !important;

    }

    /* 3. If your template uses a shadow on the circle when active */
    .switch.primary input:checked ~ .control:after {
        border-color: #ff8c00 !important; 
        box-shadow: none !important;

    }

</style>

<!-- @endsection -->
