@extends('core::base.layouts.master')
@section('title')
    {{ translate('Product Reviews') }}
@endsection
@section('custom_css')
    <link href="{{ asset('backend/assets/css/ratings.css') }}" rel="stylesheet" />
@endsection
@section('main_content')
    <div class="row">
        <div class="col-12">
            <div class="card bg-transparent mb-20">

                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="" style="font-size: 30px;">{{ translate('Product Reviews') }}</h4>
                    </div>
                </div>

            </div>
        </div>
        <div class="col-12 mb-20">
            <div class="card p-2" style="border-radius: 12px !important; overflow: hidden !important;">
                <div class="mb-2">

                </div>
                <div class="px-2 filter-area">
                    <form method="get" action="{{ route('plugin.tlcommercecore.product.reviews.list') }}">

                        <div class="row">

                            <div class="col-md-4 mb-3">

                                <label class="font-16 bold black d-block mb-2">{{ translate('Page') }}</label>
                                <select class="theme-input-style w-100" name="per_page">
                                    <option value="">{{ translate('Per page') }}</option>
                                    <option value="20" @selected(request()->has('per_page') && request()->get('per_page') == '20')>20</option>
                                    <option value="50" @selected(request()->has('per_page') && request()->get('per_page') == '50')>50</option>
                                    <option value="all" @selected(request()->has('per_page') && request()->get('per_page') == 'all')>All</option>
                                </select>

                            </div>

                            <div class="col-md-4 mb-3">

                                <label class="font-16 bold black d-block mb-2">{{ translate('Visibility') }}</label>
                                <select class="theme-input-style w-100" name="status">
                                    <option value="">{{ translate('Visibility') }}</option>
                                    <option value="{{ config('settings.general_status.active') }}" @selected(request()->has('status') && request()->get('status') == config('settings.general_status.active'))>
                                        {{ translate('Visible') }}</option>
                                    <option value="{{ config('settings.general_status.in_active') }}" @selected(request()->has('status') && request()->get('status') == config('settings.general_status.in_active'))>
                                        {{ translate('Hide') }}</option>
                                </select>
                        
                            </div>

                            <div class="col-md-4 mb-3">

                                <label class="font-16 bold black d-block mb-2">{{ translate('Filter By Rating') }}</label>
                                <select class="theme-input-style w-100" name="rating">
                                    <option value="">{{ translate('Filter by rating') }}</option>
                                    <option value="5" @selected(request()->has('rating') && request()->get('rating') == '5')>5</option>
                                    <option value="4" @selected(request()->has('rating') && request()->get('rating') == '4')>4</option>
                                    <option value="3" @selected(request()->has('rating') && request()->get('rating') == '3')>3</option>
                                    <option value="2" @selected(request()->has('rating') && request()->get('rating') == '2')>2</option>
                                    <option value="1" @selected(request()->has('rating') && request()->get('rating') == '1')>1</option>
                                </select>
                            </div>

                            <div class="col-md-4 mb-3">

                                <label class="font-16 bold black d-block mb-2">{{ translate('Filter By Search') }}</label>
                                <input type="text" name="search" class="theme-input-style w-100"
                                    value="{{ request()->has('search') ? request()->get('search') : '' }}"
                                    placeholder="Enter order code, product name , customer name">
                            </div>

                            <div class="col-md-4 mb-3 d-flex align-items-end" style="gap: 5px;">

                                <a class="btn long btn-danger w-100" style="background: white !important; color: black !important; border: 1px solid black; border-radius: 6px !important; box-shadow: none !important;"
                                    href="{{ route('plugin.tlcommercecore.product.reviews.list') }}">{{ translate('Clear Filter') }}</a>

                                <button type="submit" class="btn long w-100 btn-orange">{{ translate('Filter') }}</button>
                            </div>
                            
                        </div>

                    </form>

                    

                </div>

            </div>
        </div>

        <div class="col-12 mb-20">
            <div class="card" style="border-radius: 12px !important; overflow: hidden !important;">
                <div class="">

                </div>
                <div class="table-responsive">
                    <table id="reviewTable" class="hoverable text-nowrap border-top2">
                        <thead style="background: #F3F4F6;">
                            <tr>
                                <th>
                                    #
                                </th>
                                <th>{{ translate('Product') }}</th>
                                <th>{{ translate('Customer') }}</th>
                                <th>{{ translate('Order') }}</th>
                                <th>{{ translate('Rating') }}</th>
                                <th>{{ translate('Status') }}</th>
                                <th>{{ translate('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($reviews->count() > 0)
                                @foreach ($reviews as $key => $review)
                                    <tr>
                                        <td>
                                            {{ $key + 1 }}
                                        </td>
                                        <td>
                                            <a href="{{ route('plugin.tlcommercecore.product.edit', ['id' => $review->product_id, 'lang' => getDefaultLang()]) }}"
                                                target="_blank">
                                                {{ $review->product_name }}
                                            </a>

                                        </td>
                                        <td>
                                            <a href="{{ route('plugin.tlcommercecore.customers.details', ['id' => $review->customer_id]) }}"
                                                target="_blank">
                                                {{ $review->customer_name }}
                                            </a>
                                        </td>
                                        <td>
                                            <a href="{{ route('plugin.tlcommercecore.orders.details', ['id' => $review->order_id]) }}"
                                                target="_blank">
                                                {{ $review->order_code }}
                                            </a>
                                        </td>
                                        <td>
                                            <div class="product-rating-wrapper">
                                                <i data-star="{{ $review->rating }}"
                                                    title="{{ $review->rating }}"></i><span>{{ $review->rating }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <label class="switch glow primary medium">
                                                <input type="checkbox" class="change-status"
                                                    data-review="{{ $review->id }}"
                                                    {{ $review->status == '1' ? 'checked' : '' }}>
                                                <span class="control"></span>
                                            </label>
                                        </td>
                                        <td>
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
                                                    <a href="#" class="review-details"
                                                        data-review="{{ $review->id }}">
                                                        {{ translate('Details') }}
                                                    </a>
                                                    <a href="#" class="review-delete"
                                                        data-review="{{ $review->id }}">{{ translate('Delete') }}</a>
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
                        {!! $reviews->withQueryString()->onEachSide(1)->links('pagination::bootstrap-5-custom') !!}
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!--Delete Modal-->
    <div id="delete-modal" class="delete-modal modal fade show" aria-modal="true">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title h6">{{ translate('Delete Confirmation') }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <p class="mt-1">{{ translate('Are you sure to delete this') }}?</p>
                    <form method="POST" action="{{ route('plugin.tlcommercecore.product.reviews.delete') }}">
                        @csrf
                        <input type="hidden" id="delete-review-id" name="id">
                        <button type="button" class="btn long mt-2 btn-danger"
                            data-dismiss="modal">{{ translate('cancel') }}</button>
                        <button type="submit" class="btn long mt-2">{{ translate('Delete') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--Delete Modal-->
    <!--Details Modal-->
    <div id="details-modal" class="details-modal modal fade show" aria-modal="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title h6 font-weight-bold">{{ translate('Review Details') }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <div class="detail-content"></div>
                </div>
            </div>
        </div>
    </div>
    <!--Details Modal-->
@endsection
@section('custom_scripts')
    <script>
        (function($) {
            "use strict";
            /**
             * 
             * Change status 
             * 
             * */
            $('.change-status').on('click', function(e) {
                e.preventDefault();
                let $this = $(this);
                let id = $this.data('review');
                $.post('{{ route('plugin.tlcommercecore.product.reviews.status.change') }}', {
                    _token: '{{ csrf_token() }}',
                    id: id
                }, function(data) {
                    location.reload();
                })
            });

            /**
             * Get review details
             **/
            $('.review-details').on('click', function(e) {
                e.preventDefault();
                let id = $(this).data('review');
                $.post('{{ route('plugin.tlcommercecore.product.reviews.details') }}', {
                    _token: '{{ csrf_token() }}',
                    id: id
                }, function(data) {
                    $('.detail-content').html(data);
                    $('#details-modal').modal('show');
                })
            });
            /**
             * Delete review
             **/
            $('.review-delete').on('click', function(e) {
                e.preventDefault();
                let id = $(this).data('review');
                $('#delete-review-id').val(id);
                $('#delete-modal').modal('show');
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