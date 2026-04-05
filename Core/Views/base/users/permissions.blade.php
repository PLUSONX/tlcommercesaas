@extends('core::base.layouts.master')
@section('title')
    {{ translate('Permissions') }}
@endsection
@section('custom_css')
    <!-- ======= BEGIN PAGE LEVEL PLUGINS STYLES ======= -->
    <!-- <link rel="stylesheet" href="{{ asset('/public/backend/assets/plugins/data-table/css/jquery.dataTables.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('/public/backend/assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('/public/backend/assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('/public/backend/assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}"> -->

            <link rel="stylesheet" href="{{ asset('backend/assets/plugins/data-table/css/jquery.dataTables.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('backend/assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('backend/assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('backend/assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    <!-- ======= END BEGIN PAGE LEVEL PLUGINS STYLES ======= -->
@endsection
@section('main_content')
    <div class="row">

        <div class="col-md-8 offset-2">
            <div class="card bg-transparent mb-20">

                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="" style="font-size: 30px;">{{ translate('Permissions') }}</h4>
                          
                    </div>
                </div>

            </div>
        </div>
        <!-- Permission List-->
        <div class="col-md-8 offset-2">
            <div class="card mb-2" style="border-radius: 12px !important; overflow: hidden !important;">
            <!-- <div class="card mb-30" > -->
                <!-- <div class="card-body">
                    <div class="d-sm-flex justify-content-between align-items-center">
                        <h4 class="font-20">{{ translate('Permissions') }}</h4>
                    </div>
                </div> -->
                <div class="table-responsive" style="margin-top: 35px;">
                    <table class="hoverable text-nowrap border-top2 " id="permission_table">
                        <thead style="background: #F3F4F6;">
                            <tr>
                                <th>#</th>
                                <th class="text-center">{{ translate('Module Name') }}
                                </th>
                                <th class="text-center">{{ translate('Permission Name') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $key = 1;
                            @endphp
                            @foreach ($permissions as $permission)
                                <tr>
                                    <td>{{ $key }}</td>
                                    <td class="text-center">{{ $permission->module_name }}</td>
                                    <td class="text-center">{{ $permission->permission_name }}</td>
                                </tr>
                                @php
                                    $key++;
                                @endphp
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- Permission List-->
    </div>
@endsection
@section('custom_scripts')
    <!-- <script src="{{ asset('/public/backend/assets/plugins/data-table/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('/public/backend/assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('/public/backend/assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}">
    </script>
    <script src="{{ asset('/public/backend/assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}">
    </script> -->
     <script src="{{ asset('backend/assets/plugins/data-table/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('backend/assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('backend/assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}">
    </script>
    <script src="{{ asset('backend/assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}">
    </script>
    <script>
        (function($) {
            "use strict";
            $("#permission_table").DataTable();
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
