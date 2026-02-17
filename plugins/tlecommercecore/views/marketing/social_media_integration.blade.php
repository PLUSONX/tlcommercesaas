@php
    $facebook = $integrationSettings['facebook_pixel'] ?? null;
    $facebookSettings = $facebook ? json_decode($facebook->settings, true) : [];
@endphp
@extends('core::base.layouts.master')
@section('title')
    {{ translate('Social Media Integration') }}
@endsection
@section('custom_css')
@endsection
@section('main_content')

<div class="card mb-4">
    <form action="{{ route('plugin.tlcommercecore.marketing.social.media.integration.update') }}" method="POST">
        @csrf
        
        <div class="card-header bg-transparent py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Facebook Pixel</h5>

            <div class="form-check form-switch d-flex align-items-center m-0 p-0">
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
            </div>
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
                <button type="submit" class="btn btn-primary px-4">{{ translate('Update Settings') }}</button>
            </div>
        </div>
    </form>
</div>

<!-- <div class="card mb-4">

    <form action="{{ route('plugin.tlcommercecore.marketing.social.media.integration.update') }}" method="POST">
        
        <div class="card-header d-flex justify-content-between align-items-center">
            
            <h5 class="mb-0">Facebook Pixel</h5>
    
            <div class="form-check form-switch d-flex align-items-center mb-0" style="min-height: 1.5rem;">
                    <input class="form-check-input" 
                            type="checkbox" 
                            role="switch" 
                            id="facebookPixelToggle" 
                            name="facebook_pixel[is_active]"
                            value="1"
                            style="width: 2.5em; height: 1.25em; cursor: pointer; margin-top: 0;"
                            {{ $facebook && $facebook->is_active ? 'checked' : '' }}>
                    
                    <label class="form-check-label fw-medium ms-2" 
                            for="facebookPixelToggle" 
                            style="cursor: pointer; font-size: 0.9rem; margin-left: 10px;">
                        Enable Facebook Pixel
                    </label>
            </div>
    
        </div>
    
        <div class="card-body">
            <div class="form-group">
                <label>Pixel ID</label>
                <input type="text"
                       name="facebook_pixel[pixel_id]"
                       class="form-control"
                       value="{{ $facebookSettings['pixel_id'] ?? '' }}"
                       placeholder="Enter Facebook Pixel ID">
            </div>
        </div>
    
        <div class="form-row">
            <div class="col-12 text-right">
                <button type="submit" class="btn long">{{ translate('Update') }}</button>
            </div>
        </div>

    </form>

    

</div> -->



@endsection
@section('custom_scripts')

<script>

   
</script>

@endsection

@section('custom_css')

<style>



</style>

@endsection
