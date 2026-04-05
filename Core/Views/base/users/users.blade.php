@extends('core::base.layouts.master')
@section('title')
    {{ translate('Users') }}
@endsection
@section('custom_css')
    <!-- ======= BEGIN PAGE LEVEL PLUGINS STYLES ======= -->
         <link rel="stylesheet" href="{{ asset('backend/assets/plugins/data-table/css/jquery.dataTables.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('backend/assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('backend/assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">

    <link rel="stylesheet"
        href="{{ asset('backend/assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    <!-- <link rel="stylesheet" href="{{ asset('/public/backend/assets/plugins/data-table/css/jquery.dataTables.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('/public/backend/assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('/public/backend/assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">

    <link rel="stylesheet"
        href="{{ asset('/public/backend/assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}"> -->
    <!-- ======= END BEGIN PAGE LEVEL PLUGINS STYLES ======= -->
@endsection
@section('main_content')
    <div class="row">

        <div class="col-12">
            <div class="card bg-transparent mb-20">

                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="" style="font-size: 30px;">{{ translate('Users') }}</h4>
                            <div class="d-flex flex-wrap">
                                <a href="{{ route('core.add.user') }}"
                                    class="btn long btn-orange">{{ translate('Add New User') }}</a>
                            </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- User List-->
        <div class="col-md-12">
            <div class="card mb-2" style="border-radius: 12px !important; overflow: hidden !important;">
            <!-- <div class="card mb-30"> -->
                <!-- <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="font-20">{{ translate('Users') }}</h4>
                        @if (auth()->user()->can('Create User'))
                            <div class="d-flex flex-wrap">
                                <a href="{{ route('core.add.user') }}" class="btn long">{{ translate('Add New User') }}</a>
                            </div>
                        @endif
                    </div>
                </div> -->
                <div class="table-responsive" style="margin-top: 35px;">
                    <table class="hoverable text-nowrap border-top2 " id="user_table">
                        <thead style="background: #F3F4F6;">
                            <tr>
                                <th>#</th>
                                <th>{{ translate('Image') }}</th>
                                <!-- <th class="text-center">{{ translate('UID') }}</th> -->
                                <th class="text-center">{{ translate('Name') }}</th>
                                <th class="text-center">{{ translate('Email') }}</th>
                                <th>{{ translate('Roles') }}</th>
                                @if (auth()->user()->can('Edit User'))
                                    <th>{{ translate('Status') }}</th>
                                @endif
                                @if (auth()->user()->can('Edit User') ||
                                        auth()->user()->can('Delete User'))
                                    <th>{{ translate('Actions') }}</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $key => $user)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="img img-45">
                                                <img src="{{ asset(str_replace('public/', '', getFilePath($user->image))) }}"
     alt="{{ $user->name }}">
                                                <!-- <img src="{{ asset(getFilePath($user->image)) }}"
                                                    alt="{{ $user->name }}"> -->
                                            </div>
                                        </div>
                                    </td>
                                    <!-- <td class="text-center">{{ $user->uid }}</td> -->
                                    <td class="text-center">{{ $user->name }}</td>
                                    <td class="text-center">{{ $user->email }}</td>
                                    <td>
                                        @php
                                            $roles = $user->getRoleNames();
                                        @endphp
                                        @foreach ($roles as $roleKey => $role)
                                            {{ $role }}
                                            @if ($roleKey + 1 != $roles->count())
                                                ,
                                            @endif
                                        @endforeach
                                    </td>
                                    @if (auth()->user()->can('Edit User'))
                                        <td>
                                            @if ($user->id != getSupperAdminId())
                                                <label class="switch success medium">
                                                    <input type="checkbox" class="user_status"
                                                        id="user_status_{{ $user->id }}" name="status"
                                                        @checked($user->status == config('settings.general_status.active'))
                                                        onchange="updateUserStatus('{{ $user->id }}')">
                                                    <span class="control"></span>
                                                </label>
                                            @endif
                                            @if ($user->id == getSupperAdminId())
                                                @if ($user->status == config('settings.general_status.active'))
                                                    <p class="badge badge-success"
                                                        title="Super admin status can not be change">
                                                        {{ translate('Active') }}</p>
                                                @else
                                                    <p class="badge badge-danger"
                                                        title="Super admin status can not be change">
                                                        {{ translate('Inactive') }}</p>
                                                @endif
                                            @endif
                                        </td>
                                    @endif
                                    @if (auth()->user()->can('Edit User') ||
                                            auth()->user()->can('Delete User'))
                                        <td>
                                            <div class="dropdown-button">
                                                <a href="#" class="d-flex align-items-center" data-toggle="dropdown">
                                                    <div class="menu-icon style--two mr-0">
                                                        <span></span>
                                                        <span></span>
                                                        <span></span>
                                                    </div>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    @if ($user->id != getSupperAdminId())
                                                        @if (auth()->user()->can('Edit User'))
                                                            <a href="{{ route('core.edit.user', $user->id) }}">Edit</a>
                                                        @endif
                                                        @if (auth()->user()->can('Delete User'))
                                                            <a href="#"
                                                                onclick="deleteConfirmation('{{ $user->id }}')">Delete</a>
                                                        @endif
                                                    @else
                                                        <p class=" alert alert-danger">Has no action for super admin</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- User List-->

        <!--Delete Modal-->
        <div id="delete-modal" class="delete-modal modal fade show" aria-modal="true">
            <div class="modal-dialog modal-sm modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title h6">{{ translate('Delete Confirmation') }}</h4>
                    </div>
                    <div class="modal-body text-center">
                        <p class="mt-1">{{ translate('Are you sure to delete this user') }}?</p>
                        <form method="POST" action="{{ route('core.user.delete') }}">
                            @csrf
                            <input type="hidden" id="user_id" name="id">
                            <button type="button" class="btn long mt-2 btn-danger"
                                data-dismiss="modal">{{ translate('cancel') }}</button>
                            <button type="submit" class="btn long mt-2">{{ translate('Delete') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!--Delete Modal-->
    </div>
@endsection
@section('custom_scripts')
     <script src="{{ asset('backend/assets/plugins/data-table/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('backend/assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('backend/assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}">
    </script>
    <script src="{{ asset('backend/assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}">
    </script>
    <!-- <script src="{{ asset('/public/backend/assets/plugins/data-table/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('/public/backend/assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('/public/backend/assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}">
    </script>
    <script src="{{ asset('/public/backend/assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}">
    </script> -->

    <script type="application/javascript">
        (function($) {
            "use strict";
            $("#user_table").DataTable();
        })(jQuery);

        /**
         * Will request to update user status
         */
        function updateUserStatus(user_id) {
            "use strict";
            let status = 2
            if ($('#user_status_' + user_id).is(":checked")) {
                status = 1
            }
            $.post("{{ route('core.update.user.status') }}", {
                    _token: '{{ csrf_token() }}',
                    id: user_id,
                    status: status
                },
                function(data, status) {
                    toastr.success("User status updated successfully", "Success!");
                }).fail(function(xhr, status, error) {
                toastr.error("Unable to update user status", "!");
            });
        }

        /**
         * show delete confirmation modal
         */
        function deleteConfirmation(user_id) {
            "use strict";
            $("#user_id").val(user_id);
            $('#delete-modal').modal('show');
        }
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