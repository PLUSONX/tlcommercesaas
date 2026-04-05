@php
    use Plugin\TlcommerceCore\Repositories\SettingsRepository;
    $active_tab = request()->has('tab') && request()->get('tab') != null ? request()->get('tab') : 'general';
@endphp

@extends('core::base.layouts.master')
@section('title')
    {{ translate('Ecommerce Settings') }}
@endsection
<!-- @section('custom_css')
    <style>
        @media only screen and (max-width: 767px) {
            .theme-option-tab-wrap {
                grid-template-columns: 51px 1fr !important;
            }
        }
    </style>
@endsection -->

@section('main_content')
<form id="ecommerce-settings-form">
    <!-- <div class="theme-option-container" style="border-radius: 12px !important; overflow: hidden !important;"> -->
            <!-- <div class="theme-option-sticky d-flex align-items-center justify-content-between bg-white border-bottom2 p-3">
                <div class="theme-option-logo d-none d-sm-block">
                    <h4>{{ translate('Ecommerce Settings test') }}</h4>
                </div>
            </div> -->

     <div class="row g-3 align-items-start">

        <div class="col-3">

            <div class="card" style="border-radius: 12px !important; overflow: hidden !important;">
                <div class="theme-option-tab-wrap">
                    <div class="nav flex-column py-3"  aria-orientation="vertical">
    
    
                        <a class="nav-link {{ $active_tab == 'general' ? 'active' : '' }}" 
                            href="{{ route('plugin.tlcommercecore.ecommerce.configuration', ['tab' => 'general']) }}">
                            <!-- <i class="icofont-ui-settings" title="{{ translate('General') }}"></i> -->
                             <x-lucide-sliders-horizontal class="input-icon" />
                            <span class="menu-settings-title ml-2">{{ translate('General') }}</span>
                        </a>
    
                        <a class="nav-link {{ $active_tab == 'products' ? 'active' : '' }}"
                            href="{{ route('plugin.tlcommercecore.ecommerce.configuration', ['tab' => 'products']) }}">
                            <!-- <i class="icofont-bucket1" title="{{ translate('Products') }}"></i> -->
                             <x-lucide-barcode class="input-icon" />
                            <span class="menu-settings-title ml-2">{{ translate('Products') }}</span>
                        </a>
    
                        <a class="nav-link {{ $active_tab == 'checkout' ? 'active' : '' }}"
                            href="{{ route('plugin.tlcommercecore.ecommerce.configuration', ['tab' => 'checkout']) }}">
                            <!-- <i class="icofont-cart" title="{{ translate('Checkout') }}"></i> -->
                             <x-lucide-credit-card class="input-icon" />
                            <span class="menu-settings-title ml-2">{{ translate('Checkout') }}</span>
                        </a>
    
                        <a class="nav-link {{ $active_tab == 'customers' ? 'active' : '' }}"
                            href="{{ route('plugin.tlcommercecore.ecommerce.configuration', ['tab' => 'customers']) }}">
                            <!-- <i class="icofont-people" title="{{ translate('Customers') }}"></i> -->
                            <x-lucide-users class="input-icon" />
                            <span class="menu-settings-title ml-2">{{ translate('Customers') }}</span>
                        </a>
    
                        <a class="nav-link {{ $active_tab == 'orders' ? 'active' : '' }}"
                            href="{{ route('plugin.tlcommercecore.ecommerce.configuration', ['tab' => 'orders']) }}">
                            <!-- <i class="icofont-handshake-deal" title="{{ translate('Orders') }}"></i> -->
                            <x-lucide-box class="input-icon" />
                            <span class="menu-settings-title ml-2">{{ translate('Orders') }}</span>
                        </a>
    
                        <!-- <a class="nav-link {{ $active_tab == 'payments' ? 'active' : '' }}"
                            href="{{ route('plugin.tlcommercecore.ecommerce.configuration', ['tab' => 'payments']) }}">
                            <i class="icofont-pay" title="{{ translate('Payments') }}"></i>
                            <span>{{ translate('Payments') }}</span>
                        </a> -->
    
                        <!-- @if (isActivePluging('wallet'))
                            <a class="nav-link {{ $active_tab == 'wallet' ? 'active' : '' }}"
                                href="{{ route('plugin.tlcommercecore.ecommerce.configuration', ['tab' => 'wallet']) }}">
                                <i class="icofont-wallet" title="{{ translate('Wallet') }}"></i>
                                <span>{{ translate('Wallet') }}</span>
                            </a>
                        @endif -->
    
                        <a class="nav-link {{ $active_tab == 'invoice' ? 'active' : '' }}"
                            href="{{ route('plugin.tlcommercecore.ecommerce.configuration', ['tab' => 'invoice']) }}">
                            <!-- <i class="icofont-copy-invert" title="{{ translate('Invoice') }}"></i> -->
                            <x-lucide-receipt-text class="input-icon" />
                            <span class="menu-settings-title ml-2">{{ translate('Invoice') }}</span>
                        </a>
    
                        <a class="nav-link {{ $active_tab == 'email-notification' ? 'active' : '' }}"
                            href="{{ route('plugin.tlcommercecore.ecommerce.configuration', ['tab' => 'email-notification']) }}">
                            <!-- <i class="icofont-ui-email" title="{{ translate('Email Notification') }}"></i> -->
                            <x-lucide-mail class="input-icon" />
                            <span class="menu-settings-title ml-2">{{ translate('Email Notification') }}</span>
                        </a>
    
                        <!-- <a class="nav-link {{ $active_tab == 'tax' ? 'active' : '' }}"
                            href="{{ route('plugin.tlcommercecore.ecommerce.configuration', ['tab' => 'tax']) }}">
                            <i class="icofont-money-bag" title="{{ translate('Tax') }}"></i>
                            <span>{{ translate('Tax') }}</span>
                        </a> -->
    
                        @if (isActivePluging('multivendor'))
                            <a class="nav-link {{ $active_tab == 'shop' ? 'active' : '' }}"
                                href="{{ route('plugin.tlcommercecore.ecommerce.configuration', ['tab' => 'shop']) }}">
                                <!-- <i class="icofont-prestashop" title="{{ translate('Shop Settings') }}"></i> -->
                            <x-lucide-store class="input-icon" />
                                <span class="menu-settings-title ml-2">{{ translate('Shop Settings') }}</span>
                            </a>
                        @endif
                    </div>
                </div>
            </div>

        </div>


        <div class="col-9 p-2">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 style="font-size: 30px;">{{ translate('Ecommerce Settings') }}</h4>
            </div>


            <div class="card" style="border-radius: 12px !important; overflow: hidden !important;">
                    <div class="tab-content">
                        <!--General Settings-->
                        <div class="tab-pane fade {{ $active_tab == 'general' ? 'show active' : '' }}" id="general">
                            @includeIf('plugin/tlecommercecore::ecommerce-settings.general')
                        </div>
                        <!--End General Settings-->
                        <!--Product Settings-->
                        <div class="tab-pane fade {{ $active_tab == 'products' ? 'show active' : '' }}" id="products">
                            @includeIf('plugin/tlecommercecore::ecommerce-settings.products')
                        </div>
                        <!--End Product Settings-->
                        <!--Checkout Settings-->
                        <div class="tab-pane fade {{ $active_tab == 'checkout' ? 'show active' : '' }}" id="checkout">
                            @includeIf('plugin/tlecommercecore::ecommerce-settings.checkout')
                        </div>
                        <!--End Checkout Settings-->
                        <!--Customer Settings-->
                        <div class="tab-pane fade {{ $active_tab == 'customers' ? 'show active' : '' }}" id="customers">
                            @includeIf('plugin/tlecommercecore::ecommerce-settings.customer')
                        </div>
                        <!--End Customer Settings-->
                        <!--Order Settings-->
                        <div class="tab-pane fade {{ $active_tab == 'orders' ? 'show active' : '' }}" id="orders">
                            @includeIf('plugin/tlecommercecore::ecommerce-settings.orders')
                        </div>
                        <!--End Order Settings-->
                        <!--Payment Settings-->
                        <div class="tab-pane fade {{ $active_tab == 'payments' ? 'show active' : '' }}" id="payments">
                            @includeIf('plugin/tlecommercecore::ecommerce-settings.payment')
                        </div>
                        <!--End Payment Settings-->
                        <!--Wallet Settings-->
                        <!-- <div class="tab-pane fade {{ $active_tab == 'wallet' ? 'show active' : '' }}" id="wallet">
                            @includeIf('plugin/tlecommercecore::ecommerce-settings.wallet')
                        </div> -->
                        <!--End Wallet Settings-->
                        <!--Invoice Settings-->
                        <div class="tab-pane fade {{ $active_tab == 'invoice' ? 'show active' : '' }}" id="invoice">
                            @includeIf('plugin/tlecommercecore::ecommerce-settings.invoice')
                        </div>
                        <!--End Invoice Settings-->
                        <!--Email Notification Settings-->
                        <div class="tab-pane fade {{ $active_tab == 'email-notification' ? 'show active' : '' }}"
                            id="emailNotification">
                            @includeIf('plugin/tlecommercecore::ecommerce-settings.email-notification')
                        </div>
                        <!--End Email Notification Settings-->
                        <!--Tax Settings-->
                        <!-- <div class="tab-pane fade {{ $active_tab == 'tax' ? 'show active' : '' }}" id="tax">
                            @includeIf('plugin/tlecommercecore::ecommerce-settings.tax')
                        </div> -->
                        <!--End Tax Settings-->
                        <!--Shop Settings-->
                        @if (isActivePluging('multivendor'))
                            <div class="tab-pane fade {{ $active_tab == 'shop' ? 'show active' : '' }}" id="shopSettings">
                                @includeIf('plugin/tlecommercecore::ecommerce-settings.shop')
                            </div>
                        @endif
                        <!--End Shop Settings-->
                    </div>

                <div class="theme-option-sticky d-flex justify-content-end bg-white border-top2 p-3">
                    <div class="theme-option-action_bar">
                        <!-- <button class="btn long ecommerce-settings-update-btn">
                            {{ translate('Save Changes') }}
                        </button> -->
                        <button class="btn long btn-orange">
                            {{ translate('Save Changes') }}
                        </button>
                    </div>
                </div>
            </div>


        </div>

    </div>

        
    </form>
    @include('core::base.media.partial.media_modal')
@endsection
@section('custom_scripts')
    <script>
        (function($) {
            "use strict";
            initDropzone();
            /**
             * Enable and disable product review settings
             * 
             **/
            $('.enable-product-review').on('change', function(e) {
                if ($('input[name="enable_product_reviews"]').is(':checked')) {
                    $('.product-review-setting-group').removeClass('d-none');
                } else {
                    $('.product-review-setting-group').addClass('d-none');
                }
            });
            /**
             *Enable and disable order amount
             *  
             **/
            $('.enable-minumun-order-amount').on('change', function(e) {
                if ($('input[name="enable_minumun_order_amount"]').is(":checked")) {
                    $('.minimum-order-amount').removeClass('d-none');
                } else {
                    $('.minimum-order-amount').addClass('d-none');
                }
            });
            /**
             * Enable and disable coupon
             * 
             **/
            $('.enable-coupon-in-checkout').on('change', function(e) {
                if ($('input[name="enable_coupon_in_checkout"]').is(':checked')) {
                    $('.multiple-coupon-checkout').removeClass('d-none')
                } else {
                    $('.multiple-coupon-checkout').addClass('d-none')
                }
            });
            /**
             * Generate shop slug
             * 
             **/
            $(".shop-name").change(function(e) {
                e.preventDefault();
                let name = $(".shop-name").val();
                let permalink = string_to_slug(name);
                $("#permalink").html(permalink);
                $("#permalink_input_field").val(permalink);
                $(".permalink-input-group").removeClass("d-none");
                $(".permalink-editor").addClass("d-none");
                $(".permalink-edit-btn").removeClass("d-none");
            });
            /*edit permalink*/
            $(".permalink-edit-btn").on("click", function(e) {
                e.preventDefault();
                let permalink = $("#permalink").html();
                $("#permalink-updated-input").val(permalink);
                $(".permalink-edit-btn").addClass("d-none");
                $(".permalink-editor").removeClass("d-none");
            });
            /*Cancel permalink edit*/
            $(".permalink-cancel-btn").on("click", function(e) {
                e.preventDefault();
                $("#permalink-updated-input").val();
                $(".permalink-editor").addClass("d-none");
                $(".permalink-edit-btn").removeClass("d-none");
            });
            /*Update permalink*/
            $(".permalink-save-btn").on("click", function(e) {
                e.preventDefault();
                let input = $("#permalink-updated-input").val();
                let updated_permalink = string_to_slug(input);
                $("#permalink_input_field").val(updated_permalink);
                $("#permalink").html(updated_permalink);
                $(".permalink-editor").addClass("d-none");
                $(".permalink-edit-btn").removeClass("d-none");
            });
            /**
             * Save ecommmerce settings
             * 
             * 
             **/
            $('.ecommerce-settings-update-btn').on('click', function(e) {
                e.preventDefault();
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    },
                    type: "POST",
                    data: $("#ecommerce-settings-form").serialize(),
                    url: '{{ route('plugin.tlcommercecore.ecommerce.configuration.update') }}',
                    success: function(response) {
                        if (response.success) {
                            toastr.success('{{ translate('Updated successfully') }}');
                        } else {
                            toastr.error('{{ translate('Update Failed. Please try again') }}');
                        }
                    },
                    error: function(response) {
                        if (response.status == 422) {
                            $.each(response.responseJSON.errors, function(field_name, error) {
                                toastr.error(error);
                            })
                        } else {
                            toastr.error('{{ translate('Update Failed. Please try again') }}');
                        }
                    }
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

        /* Target the nav-link only when it has the .active class */
    .nav-link.active {
        background-color: #ff5a1f !important;
        color: #ffffff !important;
        border-radius: 12px; /* Optional: adds a slight curve to the background */
        width: 75%;
        margin-left: 10px;
    }

    /* Optional: Ensure the text stays white if there is a hover state */
    .nav-link.active:hover {
        color: #ffffff;
        opacity: 0.9;
    }

    .nav-link:hover {
        color: #ff5a1f !important;
        opacity: 0.9;
    }

    .nav-link.active i {
        color: #ffffff !important;
    }

    /* 2. Target the icon when hovering over a non-active link */
    .nav-link:hover i {
        color: #ff5a1f !important; 
        transition: color 0.3s ease;
    }

    /* 3. Ensure general icon alignment */
    .nav-link i {
        color: #666; /* Default grey color for inactive icons */
        vertical-align: middle;
        margin-right: 8px; /* Space between icon and text */
    }

    .menu-settings-title {

            color: #4A5565 !important;

        }

        .nav-link.active .menu-settings-title {
            color: #ffffff !important;
        }

        /* Optional: If you want the text to turn orange on hover for inactive tabs */
        .nav-link:hover .menu-settings-title {
            color: #ff5a1f !important;
        }

        .input-icon {
        width: 18px;
        height: 18px;
        stroke: #000000;      /* Use stroke for Lucide SVG icons */
        fill: none;           /* Ensure it's not filled in */
        pointer-events: none;
        z-index: 2;
        transition: stroke 0.3s ease; /* Smooth color swap */
    }

    /* 1. Only hover-orange if NOT active */
    .nav-link:not(.active):hover .menu-settings-title {
        color: #ff5a1f !important;
    }

    .nav-link:not(.active):hover .input-icon {
        stroke: #ff5a1f !important;
    }

    /* 2. Lock the white color when the link IS active (even on hover) */
    .nav-link.active:hover .menu-settings-title {
        color: #ffffff !important;
        opacity: 1; /* Prevents the fade effect if you don't want it */
    }

    .nav-link.active:hover .input-icon {
        stroke: #ffffff !important;
        opacity: 1;
    }

    .nav-link.active .input-icon {
        stroke: #ffffff !important;
        opacity: 1;
    }

    /* 3. Keep the background orange when active-hovered */
    .nav-link.active:hover {
        background-color: #ff5a1f !important;
    }

    @media (max-width: 900px) {
        /* Reset padding and margin for all links to prevent horizontal overflow */
        .nav-link {
            margin-left: 10px !important; /* Give them some touch space */
            margin: 5px 0; /* Vertical spacing between links */
            width: 100%;   /* Default to full width on mobile */
        }

        .nav-link.active {
            background-color: #ff5a1f !important;
            color: #ffffff !important;
            border-radius: 12px;
            width: 95%;      /* Nearly full width but with a little breathing room */
            margin-left: auto;
            margin-right: auto; /* Center the active tab */
            display: block;     /* Ensure width applies correctly */
        }

        /* Ensure text visibility on mobile hover */
        .nav-link.active:hover {
            color: #ffffff !important;
        }

        .theme-option-tab-wrap {
            padding-right: 0px;
            margin-right: 0px;

        }

        
    }
</style> 
