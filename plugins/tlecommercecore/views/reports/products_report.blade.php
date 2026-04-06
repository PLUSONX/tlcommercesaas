@extends('core::base.layouts.master')
@section('title')
    {{ translate('Products Report') }}
@endsection
@section('custom_css')
@endsection
@section('main_content')
    <div class="row">

         <div class="col-12">
            <div class="card bg-transparent mb-20">

                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="" style="font-size: 30px;">{{ translate('Products Report') }}</h4>
                    </div>
                </div>

            </div>
        </div>

        <div class="col-12">
            <div class="card mb-30" style="border-radius: 12px !important; overflow: hidden !important;">
                <!-- <div class="card-body border-bottom2 mb-20">
                    <div class="d-sm-flex justify-content-between align-items-center">
                        <h4 class="font-20">{{ translate('Products Report') }}</h4>
                    </div>
                </div> -->
                <div class="px-2 filter-area" style="margin-top: 35px;">
                    <!--Filter area-->
                    <form method="get" action="{{ route('plugin.tlcommercecore.reports.products') }}">

                        <div class="row">

                            <div class="col-md-3 mb-3">

                                <select class="theme-input-style mb-2 w-100" name="per_page">
                                    <option value="">{{ translate('Per page') }}</option>
                                    <option value="20" @selected(request()->has('per_page') && request()->get('per_page') == '20')>20</option>
                                    <option value="50" @selected(request()->has('per_page') && request()->get('per_page') == '50')>50</option>
                                    <option value="all" @selected(request()->has('per_page') && request()->get('per_page') == 'all')>All</option>
                                </select>

                            </div>

                            <div class="col-md-3 mb-3">

                                <select class="theme-input-style mb-2 w-100" name="category">
                                    <option value="">{{ translate('Product category') }}</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" @selected(request()->has('category') && $category->id == request()->get('category'))>{{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            
                            </div>

                            <div class="col-md-3 mb-3">

                                <input type="text" name="search_key" class="theme-input-style mb-2 w-100"
                                    value="{{ request()->has('search_key') ? request()->get('search_key') : '' }}"
                                    placeholder="Enter product name">
                            
                            </div>

                            <div class="col-md-3 mb-3">

                                @if (request()->has('search_key') || request()->has('category'))
                                    <a class="btn long btn-danger"
                                        style="background: white !important; color: black !important; border: 1px solid black; border-radius: 6px !important; box-shadow: none !important;"
                                        href="{{ route('plugin.tlcommercecore.reports.products') }}">
                                        {{ translate('Clear') }}
                                    </a>
                                @endif
                        
                                <button type="submit" class="btn long btn-orange">{{ translate('Filter') }}</button>
                            </div>

                        </div>
                       
                        
                        
                    </form>

                    
                    <!--End filter area-->

                </div>
                <div class="table-responsive">
                    <table id="conditionTable" class="hoverable text-nowrap">
                        <thead style="background: #F3F4F6;">
                            <tr>
                                <th>
                                    #
                                </th>
                                <th class="text-center">{{ translate('Product Name') }}</th>
                                <th class="text-center">{{ translate('Num of Sale') }}</th>
                                <th class="text-center">{{ translate('In Stock') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $total_num_sale = 0;
                                $total_stock = 0;
                            @endphp
                            @if ($data->count() > 0)
                                @foreach ($data as $key => $product)
                                    @php
                                        $total_num_sale += $product->total_sale;
                                        $total_stock += $product->variant_in_stock + $product->single_in_stock;
                                    @endphp
                                    <tr>
                                        <td>
                                            {{ $key + 1 }}
                                        </td>
                                        <td class="text-capitalize text-center">
                                            {{ $product->name }}
                                        </td>
                                        <td class="text-center">
                                            {{ $product->total_sale }}
                                        </td>
                                        <td class="text-center">
                                            @if ($product->variant_in_stock != null)
                                                {{ $product->variant_in_stock }}
                                            @else
                                                {{ $product->single_in_stock }}
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                <tr class="mb-20">
                                    <th colspan="2" class="text-right border-0">{{ translate('Total') }}</th>
                                    <th class="border-0 text-center">{{ $total_num_sale }}</th>
                                    <th class="border-0 text-center">{{ $total_stock }}</th>
                                </tr>
                            @else
                                <tr>
                                    <td colspan="4">
                                        <p class="alert alert-danger text-center">{{ translate('Nothing found') }}</p>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                    <div class="pgination px-3">
                        {!! $data->withQueryString()->onEachSide(1)->links('pagination::bootstrap-5-custom') !!}
                    </div>
                </div>
            </div>

        </div>
    </div>
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

    /* 1. The background of the switch track when ON */
    .switch.medium input:checked ~ .control {
        background-color: #ff5A1f !important;
        border-color: #ff8c00 !important;
    }

    /* 2. The sliding circle (the knob) */
    /* We usually keep this white or a very light grey for contrast */
    .switch.medium .control:after {
        background-color: #ffffff !important;
        border: 1px solid #e0e0e0;
        box-shadow: none !important;

    }

    /* 3. If your template uses a shadow on the circle when active */
    .switch.medium input:checked ~ .control:after {
        border-color: #ff8c00 !important; 
        box-shadow: none !important;

    }

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