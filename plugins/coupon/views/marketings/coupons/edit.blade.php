@extends('core::base.layouts.master')
@section('title')
    {{ translate('Edit Coupon') }}
@endsection
@section('main_content')
    <form method="POST" action="{{ route('plugin.tlcommercecore.marketing.coupon.update') }}">
        @csrf

        <div class="row g-3 align-items-start">

            <div class="col-3">

                <div class="card" style="border-radius: 12px !important; overflow: hidden !important;">

                    <div class="theme-option-tab-wrap">

                        <div class="nav flex-column py-3" aria-orientation="vertical">
                            <a class="nav-link active" data-toggle="pill" href="#coupon_general">
                                <x-lucide-settings-2 class="input-icon" />
                                <span class="menu-settings-title ml-2">{{ translate('General') }}</span>
                            </a>
                            <a class="nav-link" data-toggle="pill" href="#coupon_usage_restriction">
                                <x-lucide-shield class="input-icon" />
                                <span class="menu-settings-title ml-2">{{ translate('Usage Restriction') }}</span>
                            </a>
                            <a class="nav-link" data-toggle="pill" href="#coupon_usage_limits">
                                <x-lucide-timer class="input-icon" />
                                <span class="menu-settings-title ml-2">{{ translate('Usage Limits') }}</span>
                            </a>
                        </div>

                    </div>

                </div>

            </div>

            <div class="col-9 p-2">

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 style="font-size: 30px;">{{ translate('Edit Coupon') }}</h4>
                </div>

                <div class="card" style="border-radius: 12px !important; overflow: hidden !important;">

                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="coupon_general">
                            <div class="card">
                                <div class="card-body col-lg-12">
                                    <div class="form-row mb-20">
                                        <div class="col-sm-4">
                                            <label class="font-14 bold black">{{ translate('Coupon Code') }} </label>
                                        </div>
                                        <div class="col-md-12">
                                            <input type="text" name="coupon_code" value="{{ $coupon_details->code }}"
                                                class="theme-input-style category_name"
                                                placeholder="{{ translate('Type here') }}">
                                            <input type="hidden" name="id" value="{{ $coupon_details->id }}">
                                            @if ($errors->has('coupon_code'))
                                                <div class="invalid-input">{{ $errors->first('coupon_code') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-row mb-20">
                                        <div class="col-sm-4">
                                            <label class="font-14 bold black">{{ translate('Description') }} </label>
                                        </div>
                                        <div class="col-md-12">
                                            <textarea class="theme-input-style" name="description" placeholder="{{ translate('Description') }}">{{ $coupon_details->description }}</textarea>
                                            @if ($errors->has('description'))
                                                <div class="invalid-input">{{ $errors->first('description') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-row mb-20">
                                        <div class="col-sm-4">
                                            <label class="font-14 bold black">{{ translate('Discount Amount Type') }} </label>
                                        </div>
                                        <div class="col-md-12">
                                            <select class="theme-input-style" name="discount_amount_type">
                                                <option value="{{ config('tlecommercecore.amount_type.flat') }}"
                                                    @if ($coupon_details->discount_type == config('tlecommercecore.amount_type.flat')) selected @endif>
                                                    {{ translate('Flat') }}</option>
                                                <option value="{{ config('tlecommercecore.amount_type.percent') }}"
                                                    @if ($coupon_details->discount_type == config('tlecommercecore.amount_type.percent')) selected @endif>
                                                    {{ translate('Percentage') }}</option>
                                            </select>
                                            @if ($errors->has('discount_amount_type'))
                                                <div class="invalid-input">{{ $errors->first('discount_amount_type') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-row mb-20">
                                        <div class="col-sm-4">
                                            <label class="font-14 bold black">{{ translate('Discount Amount') }} </label>
                                        </div>
                                        <div class="col-md-12">
                                            <input placeholder="0.00" name="discount_amount"
                                                value="{{ $coupon_details->discount_amount }}" type="text"
                                                class="theme-input-style" />
                                            @if ($errors->has('discount_amount'))
                                                <div class="invalid-input">{{ $errors->first('discount_amount') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-row mb-20">
                                        <div class="col-sm-4">
                                            <label class="font-14 bold black">{{ translate('Allow Free Shipping') }} </label>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="align-items-center d-flex gap-20 wraper">
                                                <label class="switch glow primary medium">
                                                    <input type="checkbox" name="allow_free_shipping"
                                                        @if ($coupon_details->free_shipping == config('settings.general_status.active')) checked @endif>
                                                    <span class="control"></span>
                                                </label>
                                                <div>
                                                    Check this box if the coupon allows free shipping. A free shipping rate
                                                    needs to be created in your shipping zone to allow free shipping.
                                                </div>
                                            </div>
                                            @if ($errors->has('allow_free_shipping'))
                                                <div class="invalid-input">{{ $errors->first('allow_free_shipping') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-row mb-20">
                                        <div class="col-sm-4">
                                            <label class="font-14 bold black">{{ translate('Coupon Expiry Date') }} </label>
                                        </div>
                                        <div class="col-md-12">
                                            <input type="date" name="coupon_expire_date"
                                                value="{{ $coupon_details->expire_date }}" class="theme-input-style" />
                                            @if ($errors->has('coupon_expire_date'))
                                                <div class="invalid-input">{{ $errors->first('coupon_expire_date') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="coupon_usage_restriction">
                            <div class="card">
                                <div class="card-body col-lg-12">
                                    <div class="form-row mb-20">
                                        <div class="col-sm-4">
                                            <label class="font-14 bold black">{{ translate('Minimum Spend') }} </label>
                                        </div>
                                        <div class="col-md-12">
                                            <input placeholder="{{ translate('No Minimum') }}" name="minimum_spend"
                                                value="{{ $coupon_details->minimum_spend_amount }}" type="text"
                                                class="theme-input-style" />
                                            @if ($errors->has('minimum_spend'))
                                                <div class="invalid-input">{{ $errors->first('minimum_spend') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-row mb-20">
                                        <div class="col-sm-4">
                                            <label class="font-14 bold black">{{ translate('Maximum Spend') }} </label>
                                        </div>
                                        <div class="col-md-12">
                                            <input placeholder="{{ translate('No Maximum') }}" name="maximum_spend"
                                                value="{{ $coupon_details->maximum_spend_mount }}" type="text"
                                                class="theme-input-style" />
                                            @if ($errors->has('maximum_spend'))
                                                <div class="invalid-input">{{ $errors->first('maximum_spend') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-row mb-20">
                                        <div class="col-sm-4">
                                            <label class="font-14 bold black">{{ translate('Individual Use Only') }} </label>
                                        </div>
                                        <div class="col-md-12">
                                            <label class="switch glow primary medium">
                                                <input type="checkbox" name="individual_use"
                                                    @if ($coupon_details->individual_use_only == config('settings.general_status.active')) checked @endif>
                                                <span class="control"></span>
                                            </label>
                                            @if ($errors->has('individual_use'))
                                                <div class="invalid-input">{{ $errors->first('individual_use') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-row mb-20">
                                        <div class="col-sm-4">
                                            <label class="font-14 bold black">{{ translate('Exclude Sales Items') }} </label>
                                        </div>
                                        <div class="col-md-12">
                                            <label class="switch glow primary medium">
                                                <input type="checkbox" name="exclude_sale_items"
                                                    @if ($coupon_details->exclude_sale_items == config('settings.general_status.active')) checked @endif>
                                                <span class="control"></span>
                                            </label>
                                            @if ($errors->has('exclude_sale_items'))
                                                <div class="invalid-input">{{ $errors->first('exclude_sale_items') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-row mb-20">
                                        <div class="col-sm-4">
                                            <label class="font-14 bold black">{{ translate('Select Products') }} </label>
                                        </div>
                                        <div class="col-md-12">
                                            <select class="product-select w-100" name="products[]" multiple>
                                                @foreach ($products as $product)
                                                    <option data-image="{{ asset(getFilePath($product->thumbnail_image)) }}"
                                                        value="{{ $product->id }}"
                                                        {{ $coupon_details->products != null && $coupon_details->products->contains('product_id', $product->id) ? 'selected' : '' }}>
                                                        {{ $product->translation('name', getLocale()) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has('products'))
                                                <div class="invalid-input">{{ $errors->first('products') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-row mb-20">
                                        <div class="col-sm-4">
                                            <label class="font-14 bold black">{{ translate('Exclude product') }} </label>
                                        </div>
                                        <div class="col-md-12">
                                            <select class="product-select w-100" name="exclude_products[]" multiple>
                                                @foreach ($products as $product)
                                                    <option data-image="{{ asset(getFilePath($product->thumbnail_image)) }}"
                                                        value="{{ $product->id }}"
                                                        {{ $coupon_details->exclude_products != null && $coupon_details->exclude_products->contains('product_id', $product->id) ? 'selected' : '' }}>
                                                        {{ $product->translation('name', getLocale()) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has('exclude_products'))
                                                <div class="invalid-input">{{ $errors->first('exclude_products') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-row mb-20">
                                        <div class="col-sm-4">
                                            <label class="font-14 bold black">{{ translate('Brands') }} </label>
                                        </div>
                                        <div class="col-md-12">
                                            <select class="brand-select w-100" name="brands[]" multiple>
                                                @foreach ($brands as $brand)
                                                    <option value="{{ $brand->id }}"
                                                        {{ $coupon_details->brands != null && $coupon_details->brands->contains('brand_id', $brand->id) ? 'selected' : '' }}>
                                                        {{ $brand->translation('name', getLocale()) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has('brands'))
                                                <div class="invalid-input">{{ $errors->first('brands') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-row mb-20">
                                        <div class="col-sm-4">
                                            <label class="font-14 bold black">{{ translate('Exclude Brands') }} </label>
                                        </div>
                                        <div class="col-md-12">
                                            <select class="brand-select w-100" name="exclude_brands[]" multiple>
                                                @foreach ($brands as $brand)
                                                    <option value="{{ $brand->id }}"
                                                        {{ $coupon_details->exclude_brands != null && $coupon_details->exclude_brands->contains('brand_id', $brand->id) ? 'selected' : '' }}>
                                                        {{ $brand->translation('name', getLocale()) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has('exclude_brands'))
                                                <div class="invalid-input">{{ $errors->first('exclude_brands') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-row mb-20">
                                        <div class="col-sm-4">
                                            <label class="font-14 bold black">{{ translate('Categories') }} </label>
                                        </div>
                                        <div class="col-md-12">
                                            <select class="category-select w-100" name="categories[]" multiple>
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}"
                                                        {{ $coupon_details->categories != null && $coupon_details->categories->contains('category_id', $category->id) ? 'selected' : '' }}>
                                                        {{ $category->translation('name', getLocale()) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has('categories'))
                                                <div class="invalid-input">{{ $errors->first('categories') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-row mb-20">
                                        <div class="col-sm-4">
                                            <label class="font-14 bold black">{{ translate('Exclude Categories') }} </label>
                                        </div>
                                        <div class="col-md-12">
                                            <select class="category-select w-100" name="exclude_categories[]" multiple>
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}"
                                                        {{ $coupon_details->exclude_categories != null && $coupon_details->exclude_categories->contains('category_id', $category->id) ? 'selected' : '' }}>
                                                        {{ $category->translation('name', getLocale()) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has('exclude_categories'))
                                                <div class="invalid-input">{{ $errors->first('exclude_categories') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-row mb-20">
                                        <div class="col-sm-4">
                                            <label class="font-14 bold black">{{ translate('Allowed Email') }} </label>
                                        </div>
                                        <div class="col-md-12">
                                            <input placeholder="{{ translate('Allowed Email') }}"
                                                value="{{ $coupon_details->alowed_email }}" name="alowed_email"
                                                type="email" class="theme-input-style" />
                                            @if ($errors->has('alowed_email'))
                                                <div class="invalid-input">{{ $errors->first('alowed_email') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="coupon_usage_limits">
                            <div class="card">
                                <div class="card-body col-lg-9">
                                    <div class="form-row mb-20">
                                        <div class="col-sm-4">
                                            <label class="font-14 bold black">{{ translate('Usage limit per coupon') }}
                                            </label>
                                        </div>
                                        <div class="col-md-12">
                                            <input placeholder="{{ translate('Unlimited Usage') }}" type="text"
                                                name="use_limit_per_coupon"
                                                value="{{ $coupon_details->usage_limit_per_coupon }}"
                                                class="theme-input-style" />
                                            @if ($errors->has('use_limit_per_coupon'))
                                                <div class="invalid-input">{{ $errors->first('use_limit_per_coupon') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-row mb-20">
                                        <div class="col-sm-4">
                                            <label class="font-14 bold black">{{ translate('Usage limit per user') }} </label>
                                        </div>
                                        <div class="col-md-12">
                                            <input placeholder="{{ translate('Unlimited Usage') }}" name="use_limit_per_user"
                                                value="{{ $coupon_details->usage_limit_per_user }}" type="text"
                                                class="theme-input-style" />
                                            @if ($errors->has('use_limit_per_user'))
                                                <div class="invalid-input">{{ $errors->first('use_limit_per_user') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="theme-option-sticky d-flex justify-content-end bg-white border-top2 p-3">
                        <div class="theme-option-action_bar">
                            <button type="submit" class="btn long btn-orange">
                                {{ translate('Save Changes') }}
                            </button>
                        </div>
                    </div>

                </div>

            </div>

        </div>

    </form>
@endsection
@section('custom_scripts')
    <!--Select2-->
    <!-- <script src="{{ asset('backend/assets/plugins/select2/select2.min.js') }}"></script> -->
    <!-- <script>
        (function($) {
            "use strict";
            $(document).ready(function() {
                $('.product-select').select2({
                    theme: "classic",
                    closeOnSelect: false,
                    placeholder: '{{ translate('No Product Selected') }}',
                });
                $('.brand-select').select2({
                    theme: "classic",
                    closeOnSelect: false,
                    placeholder: '{{ translate('No Brand Selected') }}',
                });
                $('.category-select').select2({
                    theme: "classic",
                    closeOnSelect: false,
                    placeholder: '{{ translate('No Category Selected') }}',
                });
            });
        })(jQuery);
    </script> -->
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

    .nav-link.active {
        background-color: #ff5a1f !important;
        color: #ffffff !important;
        border-radius: 12px;
        width: 75%;
        margin-left: 10px;
    }

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

    .nav-link:hover i {
        color: #ff5a1f !important;
        transition: color 0.3s ease;
    }

    .nav-link i {
        color: #666;
        vertical-align: middle;
        margin-right: 8px;
    }

    .menu-settings-title {
        color: #4A5565 !important;
    }

    .nav-link.active .menu-settings-title {
        color: #ffffff !important;
    }

    .nav-link:hover .menu-settings-title {
        color: #ff5a1f !important;
    }

    .input-icon {
        width: 18px;
        height: 18px;
        stroke: #000000;
        fill: none;
        pointer-events: none;
        z-index: 2;
        transition: stroke 0.3s ease;
    }

    .nav-link:not(.active):hover .menu-settings-title {
        color: #ff5a1f !important;
    }

    .nav-link:not(.active):hover .input-icon {
        stroke: #ff5a1f !important;
    }

    .nav-link.active:hover .menu-settings-title {
        color: #ffffff !important;
        opacity: 1;
    }

    .nav-link.active:hover .input-icon {
        stroke: #ffffff !important;
        opacity: 1;
    }

    .nav-link.active .input-icon {
        stroke: #ffffff !important;
        opacity: 1;
    }

    .nav-link.active:hover {
        background-color: #ff5a1f !important;
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
        background: white !important;
        outline: none;
        border: 1px solid black !important;
    }

    @media (max-width: 900px) {
        .nav-link {
            margin-left: 10px !important;
            margin: 5px 0;
            width: 100%;
        }

        .nav-link.active {
            background-color: #ff5a1f !important;
            color: #ffffff !important;
            border-radius: 12px;
            width: 95%;
            margin-left: auto;
            margin-right: auto;
            display: block;
        }

        .nav-link.active:hover {
            color: #ffffff !important;
        }

        .theme-option-tab-wrap {
            padding-right: 0px;
            margin-right: 0px;
        }
    }
</style>
