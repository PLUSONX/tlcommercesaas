
@extends('core::base.layouts.master')
@section('title')
    {{ translate('Products') }}
@endsection
@section('custom_css')
    <link href="{{ asset('backend/assets/css/ratings.css') }}" rel="stylesheet" />
    <!-- <link href="{{ asset('/public/backend/assets/css/ratings.css') }}" rel="stylesheet" /> -->
    <style>
        .product-title {
            max-width: 150px;
            display: inline-block;
        }
    </style>
@endsection
@section('main_content')
    <div class="row">
        <div class="col-12">
            <div class="card bg-transparent mb-20">

                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="" style="font-size: 30px;">{{ translate('Products') }}</h4>
                        @can('Manage Add New Product')
                            <div class="d-flex flex-wrap">
                                <a href="{{ route('plugin.tlcommercecore.product.add.new') }}"
                                    class="btn long btn-orange">{{ translate('Add New Product') }}</a>
                            </div>
                        @endcan
                    </div>
                </div>

            </div>
        </div>
        <div class="col-12 mb-20">
            <div class="card p-2" style="border-radius: 12px !important; overflow: hidden !important;">
                <div class=" mb-2">

                </div>
                <div class="px-2 filter-area">
                    <!--Filter area-->
                    <form method="get" action="{{ route('plugin.tlcommercecore.product.list') }}">

                        <div class="row">

                            <div class="col-md-3 mb-3">

                                <label class="font-16 bold black d-block mb-2">{{ translate('Page') }}</label>
                                <select class="theme-input-style w-100" name="per_page">
                                    <option value="">{{ translate('Per page') }}</option>
                                    <option value="20" @selected(request()->has('per_page') && request()->get('per_page') == '20')>20</option>
                                    <option value="50" @selected(request()->has('per_page') && request()->get('per_page') == '50')>50</option>
                                    <option value="all" @selected(request()->has('per_page') && request()->get('per_page') == 'all')>All</option>
                                </select>
                            
                            </div>

                            <div class="col-md-3 mb-3">

                                <label class="font-16 bold black d-block mb-2">{{ translate('Product Status') }}</label>
                                <select class="theme-input-style w-100" name="product_status">
                                    <option value="">{{ translate('Product status') }}</option>
                                    <option value="{{ config('settings.general_status.active') }}" @selected(request()->has('product_status') && request()->get('product_status') == config('settings.general_status.active'))>
                                        {{ translate('Published') }}
                                    </option>
                                    <option value="{{ config('settings.general_status.in_active') }}" @selected(request()->has('product_status') && request()->get('product_status') == config('settings.general_status.in_active'))>
                                        {{ translate('Unpublished') }}
                                    </option>
                                </select>


                            </div>
                            

                            <div class="col-md-3 mb-3">

                                <label class="font-16 bold black d-block mb-2">{{ translate('Featured Type') }}</label>

                                <select class="theme-input-style w-100" name="product_featured">
                                    <option value="">{{ translate('Product Featured') }}</option>
                                    <option value="{{ config('settings.general_status.active') }}" @selected(request()->has('product_featured') && request()->get('product_featured') == config('settings.general_status.active'))>
                                        {{ translate('Featured') }}
                                    </option>
                                    <option value="{{ config('settings.general_status.in_active') }}" @selected(request()->has('product_featured') && request()->get('product_featured') == config('settings.general_status.in_active'))>
                                        {{ translate('Regular') }}
                                    </option>
                                </select>

                            </div>


                            <div class="col-md-3 mb-3">

                                <label class="font-16 bold black d-block mb-2">{{ translate('Variation Type') }}</label>

                                <select class="theme-input-style w-100" name="has_variation">
                                    <option value="">{{ translate('Product Variation') }}</option>
                                    <option value="{{ config('tlecommercecore.product_variant.variable') }}"
                                        @selected(request()->has('has_variation') && request()->get('has_variation') == config('tlecommercecore.product_variant.variable'))>
                                        {{ translate('Variant Product') }}
                                    </option>
                                    <option value="{{ config('tlecommercecore.product_variant.single') }}"
                                        @selected(request()->has('has_variation') && request()->get('has_variation') == config('tlecommercecore.product_variant.single'))>
                                        {{ translate('Single Product') }}
                                    </option>
                                </select>
                            </div>


                            <div class="col-md-3 mb-3">

                            <label class="font-16 bold black d-block mb-2">{{ translate('Discount Status') }}</label>
                            <select class="theme-input-style w-100" name="discount">
                                <option value="">{{ translate('Product Discount') }}</option>
                                <option value="{{ config('settings.general_status.in_active') }}" @selected(request()->has('discount') && request()->get('discount') == config('settings.general_status.in_active'))>
                                    {{ translate('No Discount') }}
                                </option>
                                <option value="{{ config('settings.general_status.active') }}" @selected(request()->has('discount') && request()->get('discount') == config('settings.general_status.active'))>
                                    {{ translate('Discounted') }}
                                </option>
                            </select>

                            </div>

                            <div class="col-md-3 mb-3">

                                <label class="font-16 bold black d-block mb-2">{{ translate('Product Name') }}</label>

                                <input type="text" name="search_key" class="theme-input-style w-100"
                                    value="{{ request()->has('search_key') ? request()->get('search_key') : '' }}"
                                    placeholder="Enter product name">

                            </div>

                            <div class="col-md-3 mb-3 d-flex align-items-end" style="gap: 5px">
                                @if(request()->has('search_key') || request()->has('payment_status'))
                                    <a class="btn long btn-danger w-100" style="background: white !important; color: black !important; border: 1px solid black; border-radius: 6px !important;" href="{{ route('plugin.tlcommercecore.product.list') }}">{{ translate('Clear') }}</a>
                                @endif
                                <button type="submit" class="btn long w-100 btn-orange" style="margin-top: 2px;">
                                    {{ translate('Filter') }}
                                </button>
                            </div>
                            
                                <!-- <button type="submit" class="btn long">{{ translate('Filter') }}</button>
                            </form>
                            @if (request()->has('search_key') || request()->has('payment_status'))
                            <a class="btn long btn-danger" href="{{ route('plugin.tlcommercecore.product.list') }}">
                                {{ translate('Clear Filter') }}
                            </a>
                            @endif -->
                        
                        </div>

                    </form>

                            <!--End filter area-->
                            
                </div>
            </div>
        </div>

        <div id="bulk-actions" class="col-12 mb-20" style="display: none;">
            <div class="card p-2" style="border-radius: 12px !important; overflow: hidden !important;">
                <div class=" mb-2">

                </div>
                <div class="px-2 filter-area">

                <div class="row">

                            <div class="col-md-3 mb-3">

                            <!--Bulk actions-->

                                <label class="font-16 bold black d-block mb-2">{{ translate('Actions') }}</label>

                                <select class="theme-input-style bulk-action-selection w-100">
                                    <option value="null">{{ translate('Bulk Action') }}</option>
                                    <option value="active">{{ translate('Make publish') }}</option>
                                    <option value="in_active">{{ translate('Make unpublish') }}</option>
                                    <option value="feature_active">{{ translate('Make feature') }}</option>
                                    <option value="feature_in_active">{{ translate('Remove from feature') }}</option>
                                    <option value="remove_discount">{{ translate('Remove discount') }}</option>
                                    <option value="delete_all">{{ translate('Delete selection') }}</option>
                                </select>

                            </div>
                                
                            <div class="col-md-3 mb-3 d-flex align-items-end">
                                
                                <button type="submit" class="btn long w-100 btn-orange fire-bulk-action" style="margin-top: 2px;">
                                    {{ translate('Apply') }}
                                </button>
                            </div>
                                <!-- <button class="btn long btn-warning fire-bulk-action">{{ translate('Apply') }}
                                </button> -->

                        
                        
                            <!--End bulk actions-->


                        </div>

                </div>
            
            </div>
        </div>

         <div class="col-12 mb-20">
            <div class="card" style="border-radius: 12px !important; overflow: hidden !important;">
                <div class="">

                </div>

            
                <div class="table-responsive">
                    <table id="productTable1" class="hoverable">
                        <thead style="background: #F3F4F6;">
                            <tr>
                                <!-- <th>
                                    <div class="d-flex align-items-center">
                                        <label class="position-relative">
                                            <input type="checkbox" name="select_all" class="select-all">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                </th> -->
                                <th>
                                    <div class="d-flex align-items-center">
                                        <label class="position-relative mr-2">
                                            <input type="checkbox" name="select_all" class="select-all">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                </th>
                                <!-- <th></th> -->
                                <th>{{ translate('Image') }}</th>
                                <th>{{ translate('Name') }}</th>
                                <th>{{ translate('Info') }}</th>
                                <th>{{ translate('Stock & Sales') }} </th>
                                <th>{{ translate('Featured') }} </th>
                                <th>{{ translate('Published') }}</th>
                                <th>{{ translate('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($products->count() > 0)
                                @foreach ($products as $key => $product)
                                    <tr>
                                        <!-- <td>
                                            <div class="d-flex align-items-center mb-3">
                                                <label class="position-relative mr-2">
                                                    <input type="checkbox" name="product_id[]" class="product-id"
                                                        value="{{ $product->id }}">
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                        </td> -->
                                        <td>
                                            <div class="d-flex align-items-center mb-3">
                                                <label class="position-relative mr-2">
                                                    <input type="checkbox" name="product_id[]" class="product-id" value="{{ $product->id }}">
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                        </td>
                                        <td>
                                            <img src="{{ str_replace('/public', '', asset(getFilePath($product->thumbnail_image))) }}" class="img-45">
                                            <!-- <img src="{{ asset(getFilePath($product->thumbnail_image)) }}" class="img-45"
                                                alt="{{ $product->name }}"> -->
                                        </td>
                                        <td >
                                            <span class="product-title text-capitalize class="text-center"">
                                                <a
                                                    href="{{ route('plugin.tlcommercecore.product.edit', ['id' => $product->id, 'lang' => getDefaultLang()]) }}">
                                                    {{ $product->translation('name', getLocale()) }}
                                                </a>
                                            </span>
                                        </td>
                                        <!--Product information-->
                                        <td>
                                            <!--Purchase price-->
                                            <div class="d-flex purchase-price">
                                                <strong>{{ translate('Purchase Price') }}: </strong>
                                                <div class="ml-1">
                                                    @if ($product->has_variant == config('tlecommercecore.product_variant.single'))
                                                        <div class="d-flex">
                                                            <div>
                                                                @if ($product->single_price->purchase_price > 0)
                                                                    {!! currencyExchange($product->single_price->purchase_price) !!}
                                                                @else
                                                                    {!! currencyExchange(0) !!}
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @else
                                                        @php
                                                            $v_price = $product->variations->toArray();
                                                        @endphp
                                                        <div class="d-flex">
                                                            <div class="d-flex">
                                                                <div class="min-purchase-price">
                                                                    @if (min(array_column($v_price, 'purchase_price')) > 0)
                                                                        {!! currencyExchange(min(array_column($v_price, 'purchase_price'))) !!}
                                                                    @else
                                                                        {!! currencyExchange(0) !!}
                                                                    @endif

                                                                </div>
                                                            </div>
                                                            <div class="sperator mx-1">-</div>
                                                            <div class="d-flex">
                                                                <div class="max-purcchase-price">
                                                                    @if (max(array_column($v_price, 'purchase_price')) > 0)
                                                                        {!! currencyExchange(max(array_column($v_price, 'purchase_price'))) !!}
                                                                    @else
                                                                        {!! currencyExchange(0) !!}
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <!--End Purchase price-->
                                            <!--Unit price-->
                                            <div class="d-flex unit-price"><strong>{{ translate('Unit Price') }}: </strong>
                                                <div class="ml-1">
                                                    @if ($product->has_variant == config('tlecommercecore.product_variant.single'))
                                                        <div class="d-flex">
                                                            <div class="single-unit-price">
                                                                @if ($product->single_price->unit_price > 0)
                                                                    {!! currencyExchange($product->single_price->unit_price) !!}
                                                                @else
                                                                    {!! currencyExchange(0) !!}
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @else
                                                        @php
                                                            $v_price = $product->variations->toArray();
                                                        @endphp
                                                        <div class="d-flex">
                                                            <div class="d-flex">
                                                                <div class="min-unit-price">
                                                                    @if (min(array_column($v_price, 'unit_price')) > 0)
                                                                        {!! currencyExchange(min(array_column($v_price, 'unit_price'))) !!}
                                                                    @else
                                                                        {!! currencyExchange(0) !!}
                                                                    @endif
                                                                </div>
                                                            </div>-
                                                            <div class="d-flex">
                                                                <div>
                                                                    @if (max(array_column($v_price, 'unit_price')) > 0)
                                                                        {!! currencyExchange(max(array_column($v_price, 'unit_price'))) !!}
                                                                    @else
                                                                        {!! currencyExchange(0) !!}
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <!--End Unit Price-->
                                            <!--Discount-->
                                            @if (getEcommerceSetting('enable_product_discount') == config('settings.general_status.active'))
                                                <div class="d-flex product discount">
                                                    <strong>{{ translate('Discount') }}: </strong>
                                                    <div class="ml-1">
                                                        <div class="d-flex">
                                                            <div>
                                                                @if ($product->discount_amount != null)
                                                                    @if ($product->discount_type == config('tlecommercecore.amount_type.flat'))
                                                                        {!! currencyExchange($product->discount_amount) !!}
                                                                    @else
                                                                        {{ $product->discount_amount }}%
                                                                    @endif
                                                                @else
                                                                    <p class="badge badge-danger">
                                                                        {{ translate('No discount') }}</p>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                            <!--End discount-->
                                            <!--Product rating-->
                                            <div class="d-flex product-rating">
                                                <strong>{{ translate('Rating') }}: </strong>
                                                <div class="ml-1">
                                                    <div class="product-rating-wrapper">
                                                        <i data-star="{{ $product->avg_rating }}"
                                                            title="{{ $product->avg_rating }}"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--End product rating-->
                                            <!--Quick action-->
                                            <!-- <div class="d-flex action-area gap-10">
                                                @if (getEcommerceSetting('enable_product_discount') == config('settings.general_status.active'))
                                                    <a href="#" class="btn-link quick-action"
                                                        data-id="{{ $product->id }}" data-action="edit_discount">
                                                        @if ($product->discount_amount != null)
                                                            {{ translate('Edit discount') }}
                                                        @else
                                                            {{ translate('Set discount') }}
                                                        @endif
                                                    </a>
                                                @endif
                                                <a href="#" class="btn-link quick-action"
                                                    data-id="{{ $product->id }}" data-action="edit_price">
                                                    {{ translate('Update price') }}
                                                </a>
                                            </div> -->
                                            <!--End quick action-->
                                        </td>
                                        <!--End product information-->
                                        <td class="text-capitalize">
                                            <div class="stock">

                                                @if ($product->has_variant == config('tlecommercecore.product_variant.single'))
                                                    <strong>{{ translate('Stock') }}: </strong>
                                                    {{ $product->single_price->quantity > 0 ? $product->single_price->quantity : 0 }}
                                                    <span>
                                                        @if ($product->unit_info != null)
                                                            {{ $product->unit_info->translation('name', getLocale()) }}
                                                        @endif
                                                    </span>
                                                    @if ($product->single_price->quantity <= $product->low_stock_quantity_alert)
                                                        @if ($product->single_price->quantity == 0)
                                                            <p class="badge badge-danger">
                                                                {{ translate('Out Of Stock') }}</p>
                                                        @else
                                                            <p class="badge badge-warning">
                                                                {{ translate('Low Stock') }}</p>
                                                        @endif
                                                    @endif
                                                @else
                                                    <strong>
                                                        <p>{{ translate('Stock') }}: </p>
                                                    </strong>
                                                    @php
                                                        $v_prices = $product->variations;
                                                    @endphp
                                                    @foreach ($v_prices as $key => $combination)
                                                        @php
                                                            $variant_array = explode('/', trim($combination->variant, '/'));
                                                            $name = '';
                                                            foreach ($variant_array as $com_key => $variant) {
                                                                $variant_com_array = explode(':', $variant);
                                                                if ($variant_com_array[0] === 'color') {
                                                                    $option_name = translate('Color');
                                                                    $choice_name = \Plugin\TlcommerceCore\Models\Colors::find($variant_com_array[1])->translation('name');
                                                                } else {
                                                                    $option_property = \Plugin\TlcommerceCore\Models\ProductAttribute::select(['id', 'name'])->find($variant_com_array[0]);
                                                                    $option_name = $option_property != null ? $option_property->translation('name') : '';
                                                                    $choice_property = \Plugin\TlcommerceCore\Models\AttributeValues::select(['id', 'name'])->find($variant_com_array[1]);
                                                                    $choice_name = $choice_property != null ? $choice_property->name : '';
                                                                }
                                                                $name .= $option_name . ' : ' . $choice_name . ' | ';
                                                            }
                                                        @endphp
                                                        {{ trim($name, ' | ') }} -
                                                        {{ $combination->quantity > 0 ? $combination->quantity : 0 }}
                                                        <span>
                                                            @if ($product->unit_info != null)
                                                                {{ $product->unit_info->translation('name', getLocale()) }}
                                                            @endif
                                                        </span>
                                                        @if ($combination->quantity <= $product->low_stock_quantity_alert)
                                                            @if ($combination->quantity == 0)
                                                                <p class="badge badge-danger mb-0">
                                                                    {{ translate('Out Of Stock') }}</p>
                                                            @else
                                                                <p class="badge badge-warning mb-0">
                                                                    {{ translate('Low Stock') }}</p>
                                                            @endif
                                                        @endif
                                                        <br>
                                                    @endforeach
                                                @endif
                                            </div>
                                            <div class="d-flex num-of-sale">
                                                <strong>{{ translate('Num of Sale') }}: </strong>
                                                <div class="ml-1">
                                                    <div class="d-flex">
                                                        <div>
                                                            {{ $product->total_sale }}
                                                        </div>
                                                        <div class="ml-1">
                                                            @if ($product->unit_info != null)
                                                                {{ $product->unit_info->translation('name', getLocale()) }}
                                                            @else
                                                                {{ translate('Times') }}
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--Quick action-->
                                            <!-- <div class="d-flex action-area gap-10">

                                                <a href="#" class="btn-link quick-action"
                                                    data-id="{{ $product->id }}" data-action="edit_stock">
                                                    {{ translate('Update stock') }}
                                                </a>
                                            </div> -->
                                            <!--End quick action-->
                                        </td>
                                        <td >
                                            <label class="switch glow primary medium">
                                                <input type="checkbox" class="change-featured"
                                                    data-product="{{ $product->id }}"
                                                    {{ $product->is_featured == config('settings.general_status.active') ? 'checked' : '' }}>
                                                <span class="control"></span>
                                            </label>
                                        </td>
                                        <td>
                                            <label class="switch glow primary medium">
                                                <input type="checkbox" class="change-status"
                                                    data-product="{{ $product->id }}"
                                                    {{ $product->status == config('settings.general_status.active') ? 'checked' : '' }}>
                                                <span class="control"></span>
                                            </label>
                                        </td>
                                        <td class="text-center">
                                            <div class="dropdown-button">
                                                <a href="#" class="d-flex align-items-center justify-content-center"
                                                    data-toggle="dropdown">
                                                    <div class="menu-icon mr-0">
                                                        <span></span>
                                                        <span></span>
                                                        <span></span>
                                                    </div>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a
                                                        href="{{ route('plugin.tlcommercecore.product.edit', ['id' => $product->id, 'lang' => getDefaultLang()]) }}">
                                                        {{ translate('Edit') }}
                                                    </a>
                                                    <a href="#" class="delete-product"
                                                        data-product="{{ $product->id }}">{{ translate('Delete') }}</a>
                                                    <a href="/products/{{ $product->permalink }}?preview=1"
                                                        target="_blank">
                                                        {{ translate('Preview') }}
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="8">
                                        <p class="alert alert-danger text-center">{{ translate('Nothing found') }}</p>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                    <div class="pgination px-3">
                        {{ $products->withQueryString()->onEachSide(1)->links('pagination::bootstrap-5-custom') }}
                    </div>
                </div>
                
            </div>
        </div>
    </div>

    <!--Delete Modal-->
    <div id="delete-modal" class="delete-modal modal fade show" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title h6">{{ translate('Delete Confirmation') }}</h4>
                </div>
                <div class="modal-body text-center">
                    <p class="mt-1">{{ translate('Are you sure to delete this') }}?</p>
                    <form method="POST" action="{{ route('plugin.tlcommercecore.product.delete') }}">
                        @csrf
                        <input type="hidden" id="delete-product-id" name="id">
                        <button type="button" class="btn long mt-2 btn-danger"
                            data-dismiss="modal">{{ translate('cancel') }}</button>
                        <button type="submit" class="btn long btn-orange mt-2">{{ translate('Delete') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--Delete Modal-->
    <!--Quick Action Modal-->
    <div id="quick-action-modal" class="quick-action-modal modal fade show" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title h6">{{ translate('Update Product Information') }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="modal-content-html">

                    </div>

                </div>
            </div>
        </div>
    </div>
    <!--End Quick Action Modal-->
@endsection
@section('custom_scripts')
    <script>
        (function($) {
            "use strict";
            /**
             * Quick action
             * 
             **/
            $('.quick-action').on('click', function(e) {
                e.preventDefault();
                $(".modal-content-html").html('');
                let action = $(this).data('action');
                let id = $(this).data('id');
                $.post('{{ route('plugin.tlcommercecore.product.quick.action.modal.view') }}', {
                    _token: '{{ csrf_token() }}',
                    action: action,
                    id: id
                }, function(data) {
                    $(".modal-content-html").html(data);
                    $("#quick-action-modal").modal('show');
                })
            });
            /**
             * 
             * Bulk action
             **/
            $('.fire-bulk-action').on('click', function(e) {
                let action = $('.bulk-action-selection').val();
                if (action != 'null') {
                    var selected_items = [];
                    $('input[name^="product_id"]:checked').each(function() {
                        selected_items.push($(this).val());
                    });
                    if (selected_items.length > 0) {
                        $.post('{{ route('plugin.tlcommercecore.product.bulk.action') }}', {
                            _token: '{{ csrf_token() }}',
                            items: selected_items,
                            action: action
                        }, function(data) {
                            location.reload();
                        })
                    } else {
                        toastr.error('{{ translate('No Item Selected') }}');
                    }
                } else {
                    toastr.error('{{ translate('No Action Selected') }}');
                }
            });
            /**
             * 
             * Change featured  status 
             * 
             * */
            $('.change-featured').on('click', function(e) {
                e.preventDefault();
                let $this = $(this);
                let id = $this.data('product');
                $.post('{{ route('plugin.tlcommercecore.product.status.featured.update') }}', {
                    _token: '{{ csrf_token() }}',
                    id: id
                }, function(data) {
                    location.reload();
                })

            });
            /**
             * 
             * Change  status 
             * 
             * */
            $('.change-status').on('click', function(e) {
                e.preventDefault();
                let $this = $(this);
                let id = $this.data('product');
                $.post('{{ route('plugin.tlcommercecore.product.status.update') }}', {
                    _token: '{{ csrf_token() }}',
                    id: id
                }, function(data) {
                    location.reload();
                })

            });
            /**
             * 
             * Delete product
             * 
             * */
            $('.delete-product').on('click', function(e) {
                e.preventDefault();
                let $this = $(this);
                let id = $this.data('product');
                console.log("productId: ", id);
                $("#delete-product-id").val(id);
                $('#delete-modal').modal('show');
            });
            /**
             * 
             * Select all product
             **/
            $('.select-all').on('change', function(e) {
                if ($('.select-all').is(":checked")) {
                    $(".product-id").prop("checked", true);
                } else {
                    $(".product-id").prop("checked", false);
                }
            });
        })(jQuery);

        $(document).ready(function() {
            // Function to check if any checkbox is ticked
            function toggleBulkDiv() {
                // Check if any product-id checkbox is checked
                const anyChecked = $('.product-id:checked').length > 0;
                
                if (anyChecked) {
                    $('#bulk-actions').fadeIn(); // Shows the div
                } else {
                    $('#bulk-actions').fadeOut(); // Hides the div
                }
            }

            // Listen for changes on individual checkboxes
            $(document).on('change', '.product-id, .select-all', function() {
                // small timeout to ensure 'select-all' logic has finished updating 'product-id'
                setTimeout(toggleBulkDiv, 50); 
            });
        });
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

    /* 4. The "Glow" effect for the track */
    /* .switch.glow.primary input:checked ~ .control {
        box-shadow: 0 0 10px rgba(255, 140, 0, 0.4) !important;
    } */

        /* 1. Change the Active Page background and border */
     .pagination .page-item.active .page-link {
        background-color: #ff5A1f !important;
        border-color: #ff5A1f !important;
        color: #ffffff !important; /* Ensure text is white on orange */
    }

    /* 2. Change the Hover state for non-active links */
    .pagination .page-item .page-link:hover {
        background-color: #ff7545 !important; /* The lighter orange we picked earlier */
        border-color: #ff7545 !important;
        color: #ffffff !important;
    }

    /* 3. Change the default text color for non-active links */
   .pagination .page-item .page-link {
        color: #ff5A1f; /* Orange text on white background */
        border-color: #dee2e6; /* Standard light border */
    }

    select.theme-input-style {
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23666' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 12px bottom 10px; /* adjust 12px to move arrow left/right */
        padding-right: 2rem;
    }

    /* 4. Optional: Style the Focus state (when clicked) to remove the blue shadow */
    /* .pagination .page-item .page-link:focus {
        box-shadow: 0 0 0 0.2rem rgba(255, 90, 31, 0.25);
    } */

</style>