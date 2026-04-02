@extends('core::base.layouts.master')
@section('title')
    {{ translate('Customers') }}
@endsection
@section('custom_css')
    <link rel="stylesheet" href="{{ asset('backend/assets/plugins/daterangepicker/daterangepicker.css') }}">
    <!-- <link rel="stylesheet" href="{{ asset('/public/backend/assets/plugins/daterangepicker/daterangepicker.css') }}"> -->
@endsection
@section('main_content')
    <div class="row">
        <div class="col-12">
            <div class="card bg-transparent mb-20">

                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="" style="font-size: 30px;">{{ translate('Customers') }}</h4>
                        
                    </div>
                </div>

            </div>
        </div>
        <div class="col-12 mb-20">
            <div class="card p-2" style="border-radius: 12px !important; overflow: hidden !important;">
                <div class="mb-2">
                   
                </div>
                <div class="px-2 filter-area d-flex align-items-center">
                    <form method="get" action="{{ route('plugin.tlcommercecore.customers.list') }}">

                        <div class="row">

                            <div class="col-md-4 mb-3">
                                <label class="font-16 bold black d-block mb-2">{{ translate('Page') }}</label>
                                <select class="theme-input-style w-100" name="per_page" >
                                    <option value="">{{ translate('Per page') }}</option>
                                    <option value="20" @selected(request()->has('per_page') && request()->get('per_page') == '20')>20</option>
                                    <option value="50" @selected(request()->has('per_page') && request()->get('per_page') == '50')>50</option>
                                    <option value="all" @selected(request()->has('per_page') && request()->get('per_page') == 'all')>All</option>
                                </select>
                            </div>


                            <div class="col-md-4 mb-3">
                                <label class="font-16 bold black d-block mb-2">{{ translate('Status') }}</label>
                                <select class="theme-input-style w-100" name="status">
                                    <option value="">{{ translate('Status') }}</option>
                                    <option value="{{ config('settings.general_status.active') }}" @selected(request()->has('status') && request()->get('status') == config('settings.general_status.active'))>
                                        {{ translate('Active') }}</option>
                                    <option value="{{ config('settings.general_status.in_active') }}" @selected(request()->has('status') && request()->get('status') == config('settings.general_status.in_active'))>
                                        {{ translate('Inactive') }}</option>
                                </select>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="font-16 bold black d-block mb-2">{{ translate('Joined Date') }}</label>
                                <input type="text" class="theme-input-style w-100" id="joinDateRange"
                                    placeholder="Filter by join date" name="join_date" readonly>
                            </div>


                            <div class="col-md-4 mb-3">
                                <label class="font-16 bold black d-block mb-2">{{ translate('Name or Email') }}</label>
                                <input type="text" name="search" class="theme-input-style w-100"
                                    value="{{ request()->has('search') ? request()->get('search') : '' }}"
                                    placeholder="Enter name, email, phone, uid">

                            </div>

                            <div class="col-md-4 mb-3 d-flex align-items-end" style="gap: 5px">

                                <a class="btn btn-danger long w-100" style="background: white !important; color: black !important; border: 1px solid black; border-radius: 6px !important; box-shadow: none !important;"
                                    href="{{ route('plugin.tlcommercecore.customers.list') }}">{{ translate('Clear') }}</a>
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
                    <table id="customerTable" class="hoverable text-nowrap border-top2">
                        <thead style="background: #F3F4F6;">
                            <tr>
                                <th>
                                    #
                                </th>
                                <th>{{ translate('Image') }}</th>
                                <th>{{ translate('Name') }}</th>
                                <th>{{ translate('Uid') }}</th>
                                <th>{{ translate('Email') }}</th>
                                <th>{{ translate('Phone') }}</th>
                                <th>{{ translate('No. of Order') }}</th>
                                <th>{{ translate('Status') }}</th>
                                <th>{{ translate('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($customers->count() > 0)
                                @foreach ($customers as $key => $customer)
                                    <tr>
                                        <td>
                                            {{ $key + 1 }}
                                        </td>
                                        <td>
                                             <a
                                                href="{{ route('plugin.tlcommercecore.customers.details', ['id' => $customer->id]) }}">
                                                <!-- <img src="{{ asset(getFilePath($customer->image)) }}" class="img-45"
                                                    alt="{{ $customer->name }}"> -->
                                                <img src="{{ asset(preg_replace('#^/public#', '', getFilePath($customer->image))) }}"
                                                    class="img-45"
                                                    alt="{{$customer->name }}"
                                        >
                                            </a>
                                            <!-- <a
                                                href="{{ route('plugin.tlcommercecore.customers.details', ['id' => $customer->id]) }}">
                                                <img src="{{ asset(getFilePath($customer->image)) }}" class="img-45"
                                                    alt="{{ $customer->name }}">
                                            </a> -->
                                        </td>
                                        <td>
                                            <a
                                                href="{{ route('plugin.tlcommercecore.customers.details', ['id' => $customer->id]) }}">
                                                {{ $customer->name }}
                                            </a>
                                        </td>
                                        <td>{{ $customer->uid }}</td>
                                        <td>{{ $customer->email }}</td>
                                        <td>{{ $customer->phone }}</td>
                                        <td>{{ $customer->total_order }}</td>
                                        <td>
                                            <label class="switch glow primary medium">
                                                <input type="checkbox" class="change-status"
                                                    data-customer="{{ $customer->id }}"
                                                    {{ $customer->status == '1' ? 'checked' : '' }}>
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
                                                    <a href="#" data-item="{!! htmlspecialchars($customer, ENT_QUOTES, 'UTF-8') !!}"
                                                        data-image="{{ getFilePath($customer->image) }}"
                                                        class="edit-customer-btn">
                                                        {{ translate('Edit') }}
                                                    </a>
                                                    <a
                                                        href="{{ route('plugin.tlcommercecore.customers.details', ['id' => $customer->id]) }}">
                                                        {{ translate('Details') }}
                                                    </a>

                                                    <a href="#" data-id="{{ $customer->id }}"
                                                        class="customer-reset-password">
                                                        {{ translate('Reset Password') }}
                                                    </a>
                                                    <a href="#" class="delete-customer"
                                                        data-customer="{{ $customer->id }}">{{ translate('Delete Customer') }}
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="9">
                                        <p class="alert alert-danger text-center">{{ translate('Nothing Found') }}</p>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                    <div class="pgination px-3">
                        {!! $customers->withQueryString()->onEachSide(1)->links('pagination::bootstrap-5-custom') !!}
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
                    <p class="mt-1">{{ translate('Are you sure to delete this customer') }}?</p>
                    <form method="POST" action="{{ route('plugin.tlcommercecore.customers.delete') }}">
                        @csrf
                        <input type="hidden" id="delete-customer-id" name="id">
                        <div class="form-row d-flex justify-content-between">
                            <button type="button" class="btn long mt-2 btn-danger"
                                data-dismiss="modal">{{ translate('cancel') }}</button>
                            <button type="submit" class="btn long mt-2">{{ translate('Delete') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--Delete Modal-->
    <!--Reset password modal Modal-->
    <div id="reset-password-modal" class="reset-password-modal modal fade show" aria-modal="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title bold h6">{{ translate('Reset Password') }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <form id="reset-passwork-form">
                        <div class="form-row mb-20">
                            <label>{{ translate('New password') }}</label>
                            <input type="password" name="password" class="theme-input-style"
                                placeholder="{{ translate('Enter new password') }}">
                        </div>
                        <div class="form-row mb-20">
                            <label>{{ translate('Confirm password') }}</label>
                            <input type="password" name="password_confirmation" class="theme-input-style"
                                placeholder="{{ translate('Confirm password') }}">
                        </div>
                        <input type="hidden" id="reset-password-customer-id" name="id">
                        <div class="btn-area d-flex justify-content-between">
                            <button type="button" class="btn long mt-2 btn-danger"
                                data-dismiss="modal">{{ translate('cancel') }}</button>
                            <button class="btn long mt-2 reset-password-btn">{{ translate('Submit') }}</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--Reset password modal-->
    <!--Edit customer Modal-->
    <div id="edit-cutomer-modal" class="edit-cutomer-modal modal fade show" aria-modal="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title bold h6">{{ translate('Customer information') }}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="customer-update-form">
                            <div class="form-row mb-20">
                                <label class="black font-14 col-12">{{ translate('Image') }}</label>
                                @include('core::base.includes.media.media_input', [
                                    'input' => 'edit_image',
                                    'data' => null,
                                ])

                            </div>
                            <div class="form-row mb-20">
                                <label class="black font-14">{{ translate('Name') }}</label>
                                <input type="text" name="name" class="theme-input-style">
                            </div>
                            <div class="form-row mb-20">
                                <label class="black font-14">{{ translate('Email') }}</label>
                                <input type="email" name="email" class="theme-input-style">
                            </div>
                            <div class="form-row mb-20">
                                <label class="col-12 black font-14">{{ translate('Phone') }}</label>
                                <input type="text" name="phone" class="theme-input-style">
                            </div>
                            <input type="hidden" name="id">
                            <div class="btn-area d-flex justify-content-between">
                                <button class="btn long mt-2 update-customer">{{ translate('Save Changes') }}</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--End customer modal-->
    @include('core::base.media.partial.media_modal')
@endsection
@section('custom_scripts')
    <script src="{{ asset('backend/assets/plugins/moment/moment.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('backend/assets/plugins/daterangepicker/daterangepicker.js') }}">
    </script>
    <!-- <script src="{{ asset('/public/backend/assets/plugins/moment/moment.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/public/backend/assets/plugins/daterangepicker/daterangepicker.js') }}">
    </script> -->
    <script>
        (function($) {
            "use strict";
            initDropzone()
            $(document).ready(function() {
                is_for_browse_file = true
                filtermedia()
            });
            /**
             *
             * delete customer
             *
             * */
            $('.delete-customer').on('click', function(e) {
                e.preventDefault();
                var id = $(this).data('customer');
                $('#delete-customer-id').val(id);
                $("#delete-modal").modal("show");
            });
            /**
             *
             * Change status
             *
             * */
            $('.change-status').on('click', function(e) {
                e.preventDefault();
                let $this = $(this);
                let id = $this.data('customer');
                $.post('{{ route('plugin.tlcommercecore.customers.change.status') }}', {
                    _token: '{{ csrf_token() }}',
                    id: id
                }, function(data) {
                    location.reload();
                })
            });
            /**
             * Reset password
             *
             **/
            $('.customer-reset-password').on('click', function(e) {
                e.preventDefault();
                let id = $(this).data('id');
                $("#reset-password-customer-id").val(id);
                $("#reset-password-modal").modal('show');
            });
            /**
             * Submit reset password form
             *
             **/
            $(".reset-password-btn").on('click', function(e) {
                $(document).find('.invalid-input').remove();
                e.preventDefault();
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    },
                    type: "POST",
                    data: $("#reset-passwork-form").serialize(),
                    url: '{{ route('plugin.tlcommercecore.customers.password.reset') }}',
                    success: function(response) {
                        if (response.success) {
                            $("#reset-password-modal").modal('hide');
                            $(document).find('[name=password]').val('');
                            $(document).find('[name=password_confirmation').val('');
                            toastr.success('{{ translate('Password updated successfully') }}');
                        } else {
                            toastr.error('{{ translate('Update Failed ') }}');
                        }
                    },
                    error: function(response) {
                        if (response.status == 422) {
                            $.each(response.responseJSON.errors, function(field_name, error) {
                                $(document).find('[name=' + field_name + ']').closest(
                                    '.theme-input-style').after(
                                    '<div class="invalid-input d-flex">' + error +
                                    '</div>')
                            })
                        } else {
                            toastr.error('{{ translate('Update Failed ') }}');
                        }
                    }
                });
            });
            /**
             *Load edit customer modal
             *
             **/
            $('.edit-customer-btn').on('click', function(e) {
                e.preventDefault();
                let customer_details = $(this).data('item');
                let img_src = $(this).data('image');
                //set data
                $(document).find('[name=name').val(customer_details.name);
                $(document).find('[name=email').val(customer_details.email);
                $(document).find('[name=phone').val(customer_details.phone);
                $(document).find('[name=id').val(customer_details.id);
                $(document).find('[name=edit_image').val(customer_details.image);
                $("#edit_image_preview").attr('src', img_src);
                $("#edit-cutomer-modal").modal('show');
            })
            /**
             * Update customer
             *
             **/
            $(".update-customer").on('click', function(e) {
                $(document).find('.invalid-input').remove();
                e.preventDefault();
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    },
                    type: "POST",
                    data: $("#customer-update-form").serialize(),
                    url: '{{ route('plugin.tlcommercecore.customers.info.update') }}',
                    success: function(response) {
                        if (response.success) {
                            $("#edit-cutomer-modal").modal('hide');
                            $(document).find('[name=name').val('');
                            $(document).find('[name=email').val('');
                            $(document).find('[name=phone').val('');
                            $(document).find('[name=id').val('');
                            $(document).find('[name=edit_image').val('');
                            $("#edit_image_preview").attr('src', '');
                            toastr.success('{{ translate('Customer updated successfully') }}');
                            location.reload();
                        } else {
                            toastr.error('{{ translate('Update Failed ') }}');
                        }
                    },
                    error: function(response) {
                        if (response.status == 422) {
                            $.each(response.responseJSON.errors, function(field_name, error) {
                                $(document).find('[name=' + field_name + ']').closest(
                                    '.theme-input-style').after(
                                    '<div class="invalid-input d-flex">' + error +
                                    '</div>')
                            })
                        } else {
                            toastr.error('{{ translate('Update Failed ') }}');
                        }
                    }
                });
            })

            /**
             * Customer secret login
             *
             **/
            $(".customer-secret-login").on('click', function(e) {
                e.preventDefault();
                let id = $(this).data('id');
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    },
                    type: "POST",
                    data: {
                        id: id
                    },
                    url: '{{ route('plugin.tlcommercecore.customers.login.secret') }}',
                    success: function(response) {
                        if (response.success) {
                            localStorage.setItem("customerDashboardInfo", JSON.stringify(response
                                .dashboard_content));
                            localStorage.setItem("isCustomerLogin", JSON.stringify(true));
                            localStorage.setItem("customerToken", JSON.stringify(response
                                .access_token));
                            localStorage.setItem("customerInfo", JSON.stringify(response.user));
                            var url = "/dashboard";
                            window.open(url, '_blank');
                        } else {
                            toastr.error('{{ translate('Login Failed ') }}');
                        }
                    },
                    error: function(response) {
                        toastr.error('{{ translate('Login Failed ') }}');
                    }
                })
            });

            function cb(start, end) {
                let initVal = '{{ request()->has('join_date') ? request()->get('join_date') : '' }}';
                $('#joinDateRange').val(initVal);
            }
            var start = moment().subtract(0, 'days');
            var end = moment();
            $('#joinDateRange').on('apply.daterangepicker', function(ev, picker) {
                let val = picker.startDate.format('YYYY-MM-DD') + ' to ' + picker.endDate.format(
                    'YYYY-MM-DD')
                $('#joinDateRange').val(val);
            });
            $('#joinDateRange').daterangepicker({
                startDate: start,
                endDate: end,
                showCustomRangeLabel: true,
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,
                        'month').endOf('month')]
                }
            }, cb);
            cb(start, end);

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