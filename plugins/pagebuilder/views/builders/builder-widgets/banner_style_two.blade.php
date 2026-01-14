@php
    $primary_text = $data['primary_text_t_'];
    $secondary_text = $data['secondary_text_t_'];

    $primary_button_text = $data['primary_button_text_t_'];
    $primary_button_url = $data['primary_button_url'];

    $secondary_button_text = $data['secondary_button_text_t_'];
    $secondary_button_url = $data['secondary_button_url'];

    /*$background_image = 'themes/default/SCREEN 1 - Home Screen.png';*/
     $background_image = $data['background_image']; 
    $background_shape_image = $data['background_shape_image'];

    $foreground_image = 'themes/default/Dashboard_Frame.png';

    /* $foreground_image = $data['foreground_image']; */
@endphp

<section class="banner style--two plugins"
    data-bg-img="{{ !empty($background_image) ? asset(getFilePath($background_image)) : '' }}">
    <!-- <img src="{{ asset($background_image) }}" alt="" class="svg plug-banner-shape"> -->
    @if (!empty($background_shape_image))
        <img src="{{ asset(getFilePath($background_shape_image)) }}" alt="" class="svg plug-banner-shape">
    @endif

    <div class="container">
    <div class="row">
        <!-- Banner Content (Top) -->
        <div class="col-12">
            <div class="banner-content">
                <div class="text-center">
                    <span class="ui-badge ui-badge-primary">Unified Business Platform</span>
                </div>
                <!-- <span class="inline-flex items-center rounded-md bg-red-50 px-2 py-1 text-xs font-medium text-red-700 inset-ring inset-ring-red-600/10 dark:bg-red-400/10 dark:text-red-400 dark:inset-ring-red-400/20">Badge</span> -->
                <div class="content text-white">
                    

                    <h1 class="text-center" style="color: #000 !important; font-size: 62px !important;">{{ $primary_text }}</h1>
                    <p class="text-center" style="color: #000 !important; font-size: 16px !important;">{{ $secondary_text }}</p>

                    <div class="banner-btn-group justify-center">
                    <a href="{{ $primary_button_url }}" class="btn-crs plug s-btn">
                        {{ $primary_button_text }}
                    </a>
                    </div>
                </div>

                <!-- <div class="banner-btn-group"> -->
                <!-- <div class="banner-btn-group justify-center">
                    <a href="{{ $primary_button_url }}" class="btn-crs plug s-btn">
                        {{ $primary_button_text }}
                    </a> -->
                    <!-- <a href="{{ $secondary_button_url }}" class="btn-book line-btn">
                        {{ $secondary_button_text }}
                    </a> -->
                <!-- </div> -->
            </div>
        </div>

        <!-- Image (Bottom) -->
        <div class="col-12">
            <div class="banner-img text-center mt-4" style="background-position: fixed !important;">
                @if (!empty($foreground_image))
                    <img src="{{ asset($foreground_image) }}" class="b-thumb" data-rjs="2" alt="">
                @endif
            </div>
        </div>
    </div>
</div>

<!-- 
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 col-md-12 order-1 order-lg-0">
                <div class="banner-content">
                    <div class="content text-white">
                        <h1 style="color: #000 !important;">{{ $primary_text }}</h1> -->
                        <!-- <h1>{{ $primary_text }}</h1> -->

                        <!-- <p>{{ $secondary_text }}</p>
                    </div>

                    <div class="banner-btn-group">
                        <a href="{{ $primary_button_url }}" class="btn-crs plug s-btn">{{ $primary_button_text }}</a>
                        <a href="{{ $secondary_button_url }}" class="btn-book line-btn">{{ $secondary_button_text }}</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-12 order-0 order-lg-1">
                <div class="banner-img text-right">
                    @if (!empty($foreground_image))
                    <img src="{{ asset($foreground_image) }}" class="b-thumb" data-rjs="2"
                            alt=""> -->
                        <!-- <img src="{{ asset(getFilePath($foreground_image)) }}" class="b-thumb" data-rjs="2"
                            alt=""> -->
                    <!-- @endif -->
                <!-- </div>
            </div>
        </div>
    </div> -->
</section>