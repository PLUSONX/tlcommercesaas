@php
    $permissions = getAllPermissions();
    $last_permission_id = getLastPermissionId();
    $permissionModules = getPermissionsModules();

@endphp
@extends('core::base.layouts.master')
@section('title')
    {{ translate('Roles') }}
@endsection
@section('custom_css')
    <!-- ======= BEGIN PAGE LEVEL PLUGINS STYLES ======= -->
    <!-- <link rel="stylesheet" href="{{ asset('/public/backend/assets/plugins/data-table/css/jquery.dataTables.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('/public/backend/assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('/public/backend/assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">

    <link rel="stylesheet"
        href="{{ asset('/public/backend/assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
         -->

          <link rel="stylesheet" href="{{ asset('backend/assets/plugins/data-table/css/jquery.dataTables.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('backend/assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('backend/assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">

    <link rel="stylesheet"
        href="{{ asset('backend/assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    <!-- ======= END BEGIN PAGE LEVEL PLUGINS STYLES ======= -->
    <style>
        .table-scroll {
            overflow-x: auto;
        }
    </style>
@endsection
@section('main_content')
    <div class="row">

        <div class="col-12">
            <div class="card bg-transparent mb-20">

                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="" style="font-size: 30px;">{{ translate('Roles') }}</h4>
                    </div>
                </div>

            </div>
        </div>
        <!-- Role List-->
        <div class="col-md-6">
            <div class="card mb-30" style="border-radius: 12px !important; overflow: hidden !important;">
                <!-- <div class="card-body">
                    <div class="d-sm-flex justify-content-between align-items-center">
                        <h4 class="font-20">{{ translate('Roles') }}</h4>
                    </div>
                </div> -->
                <div class="table-responsive" style="margin-top: 20px;">
                    <table class="hoverable text-nowrap border-top2 " id="role_table">
                        <thead style="background: #F3F4F6;">
                            <tr>
                                <th>#</th>
                                <th>{{ translate('Name') }}</th>
                                @if (auth()->user()->can('Edit Role') ||
                                        auth()->user()->can('Delete Role'))
                                    <th>{{ translate('Actions') }}</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $key = 1;
                            @endphp
                            @foreach ($roles as $role)
                                @if ($role->id != config('settings.roles.supper_admin'))
                                    <tr>
                                        <td>{{ $key }}</td>
                                        <td>{{ $role->name }}</td>
                                        @if (auth()->user()->can('Edit Role') ||
                                                auth()->user()->can('Delete Role'))
                                            <td>
                                                <div class="dropdown-button">
                                                    <a href="#" class="d-flex align-items-center"
                                                        data-toggle="dropdown">
                                                        <div class="menu-icon style--two mr-0">
                                                            <span></span>
                                                            <span></span>
                                                            <span></span>
                                                        </div>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        @if (auth()->user()->can('Edit Role'))
                                                            <a href="#"
                                                                onclick="showEditableForm('{{ $role->id }}')">Edit</a>
                                                        @endif
                                                        @if (auth()->user()->can('Delete Role'))
                                                            <a href="#"
                                                                onclick="deleteConfirmation('{{ $role->id }}')">Delete</a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                        @endif
                                    </tr>
                                    @php
                                        $key++;
                                    @endphp
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- Role List-->

        <div class="col-12">
            <div class="card bg-transparent mb-20">

                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="" style="font-size: 30px;">{{ translate('Add Role') }}</h4>
                    </div>
                </div>

            </div>
        </div>

        @if (auth()->user()->can('Create Role') ||
                auth()->user()->can('Edit Role'))
            <div class="col-md-7 mb-30">
                @if (auth()->user()->can('Create Role'))
                    <!-- Add new role-->
                    <div class="card" style="border-radius: 12px !important; overflow: hidden !important;">
                        <div class="">
                            <div class="post-head d-flex justify-content-end align-items-center mb-3 mt-3">
                                <!-- <div class="d-flex align-items-center">
                                    <div class="content">
                                        <h4 class="mb-1">{{ translate('Add Role') }}</h4>
                                    </div>
                                </div> -->
                                <div id="add_role_down_icon" class="icon" onclick="toggleRoleAddingForm()">
                                    <i class="icofont-simple-down"></i>
                                </div>
                                <div id="add_role_up_icon" class="icon" onclick="toggleRoleAddingForm()">
                                    <i class="icofont-simple-up"></i>
                                </div>
                            </div>

                            <div id="add_role">
                                <form action="{{ route('core.add.role') }}" method="POST">
                                    @csrf
                                    <div class="form-row mb-20 p-2">
                                        <div class="col-md-4">
                                            <label class="font-14 bold black">{{ translate('Name') }}</label>
                                        </div>
                                        <div class="col-md-12">
                                            <input type="text" name="role_name" class="theme-input-style w-100"
                                                value="{{ old('role_name') }}"
                                                placeholder="{{ translate('Give role name') }}">
                                            @if ($errors->has('role_name'))
                                                <div class="invalid-input">{{ $errors->first('role_name') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-row mb-20">
                                        <input type="hidden" name="permissions" id="permissions">
                                        <div class="col-sm-12 table-scroll">
                                            <h4 class="mb-3 p-2">{{ translate('Permissions') }}</h5>
                                                <table class="table">
                                                    <thead style="background: #F3F4F6;">
                                                        <tr>
                                                            <th scope="col">{{ translate('Module') }}</th>
                                                            <th scope="col">{{ translate('Feature') }}</th>
                                                            <th scope="col">{{ translate('Show') }}</th>
                                                            <th scope="col">{{ translate('Create') }}</th>
                                                            <th scope="col">{{ translate('Edit') }}</th>
                                                            <th scope="col">{{ translate('Delete') }}</th>
                                                            <th scope="col">{{ translate('Manage') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @for ($i = 0; $i < sizeof($permissionModules); $i++)
                                                            @php
                                                                $permissions = getPermissionsOfModule($permissionModules[$i]->id);
                                                                $show_permission_id = hasPermissionInThisModule($permissionModules[$i]->id, translate('Show ') . ' ' . $permissionModules[$i]->module_name);
                                                                $create_permission_id = hasPermissionInThisModule($permissionModules[$i]->id, translate('Create ') . ' ' . $permissionModules[$i]->module_name);
                                                                $edit_permission_id = hasPermissionInThisModule($permissionModules[$i]->id, translate('Edit ') . ' ' . $permissionModules[$i]->module_name);
                                                                $delete_permission_id = hasPermissionInThisModule($permissionModules[$i]->id, translate('Delete ') . ' ' . $permissionModules[$i]->module_name);
                                                                $manage_permission_id = hasPermissionInThisModule($permissionModules[$i]->id, translate('Manage ') . ' ' . $permissionModules[$i]->module_name);
                                                            @endphp
                                                            <tr>
                                                                @if ($i == 0 || $permissionModules[$i]->parent_module != $permissionModules[$i - 1]->parent_module)
                                                                    <th>{{ $permissionModules[$i]->parent_module }}</th>
                                                                @else
                                                                    <th></th>
                                                                @endif
                                                                <td>{{ $permissionModules[$i]->module_name }}</td>
                                                                @if ($show_permission_id)
                                                                    <td>
                                                                        <label class="switch success small">
                                                                            <input type="checkbox"
                                                                                id="role_has_permissions_{{ $show_permission_id }}"
                                                                                onchange="setRemovePermissionsToRole('{{ $show_permission_id }}')">
                                                                            <span class="control"></span>
                                                                        </label>
                                                                    </td>
                                                                @else
                                                                    <td></td>
                                                                @endif
                                                                @if ($create_permission_id)
                                                                    <td>
                                                                        <label class="switch success small">
                                                                            <input type="checkbox"
                                                                                id="role_has_permissions_{{ $create_permission_id }}"
                                                                                onchange="setRemovePermissionsToRole('{{ $create_permission_id }}')">
                                                                            <span class="control"></span>
                                                                        </label>
                                                                    </td>
                                                                @else
                                                                    <td></td>
                                                                @endif
                                                                @if ($edit_permission_id)
                                                                    <td>
                                                                        <label class="switch success small">
                                                                            <input type="checkbox"
                                                                                id="role_has_permissions_{{ $edit_permission_id }}"
                                                                                onchange="setRemovePermissionsToRole('{{ $edit_permission_id }}')">
                                                                            <span class="control"></span>
                                                                        </label>
                                                                    </td>
                                                                @else
                                                                    <td></td>
                                                                @endif
                                                                @if ($delete_permission_id)
                                                                    <td>
                                                                        <label class="switch success small">
                                                                            <input type="checkbox"
                                                                                id="role_has_permissions_{{ $delete_permission_id }}"
                                                                                onchange="setRemovePermissionsToRole('{{ $delete_permission_id }}')">
                                                                            <span class="control"></span>
                                                                        </label>
                                                                    </td>
                                                                @else
                                                                    <td></td>
                                                                @endif
                                                                @if ($manage_permission_id)
                                                                    <td>
                                                                        <label class="switch success small">
                                                                            <input type="checkbox"
                                                                                id="role_has_permissions_{{ $manage_permission_id }}"
                                                                                onchange="setRemovePermissionsToRole('{{ $manage_permission_id }}')">
                                                                            <span class="control"></span>
                                                                        </label>
                                                                    </td>
                                                                @else
                                                                    <td></td>
                                                                @endif
                                                            </tr>
                                                        @endfor
                                                    </tbody>
                                                </table>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-12 text-right p-4">
                                            <button type="submit" class="btn long btn-orange">{{ translate('Submit') }}</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- /Add new role-->
                @endif

                @if (auth()->user()->can('Edit Role'))

                 

                    <!-- Update new role-->
                    <div class="card mt-4" id="update_role" style="border-radius: 12px !important; overflow: hidden !important;">
                        <!-- <div class="card-body"> -->
                        <div class="">
                            <div class="post-head d-flex justify-content-between align-items-center mb-3 p-2">
                                <div class="d-flex align-items-center">
                                    <div class="content">
                                        <h4 class="mb-1">{{ translate('Update Role') }}</h4>
                                    </div>
                                </div>
                                <div id="update_role_down_icon" class="icon" onclick="hideRoleUpdateForm()">
                                    <i class="icofont-close"></i>
                                </div>
                            </div>

                            <div>
                                <div>
                                    <input type="hidden" name="role_id" id="role_id">
                                    <div class="form-row mb-20 p-2">
                                        <div class="col-md-4">
                                            <label class="font-14 bold black">{{ translate('Name') }}</label>
                                        </div>
                                        <div class="col-md-12">
                                            <input type="text" name="role_name" id="role_name"
                                                class="theme-input-style"
                                                placeholder="{{ translate('Give role name') }}">
                                            <div class="invalid-input" id="role_name_update_error"></div>
                                        </div>
                                    </div>

                                    <div class="form-row mb-20">
                                        <input type="hidden" name="permissions" id="edditable_permissions">
                                        <div class="col-sm-12 table-scroll">
                                            <div class="invalid-input" id="permissions_update_error"></div>
                                            <h4 class="mb-3 p-2">{{ translate('Permissions') }}</h5>
                                                <table class="table">
                                                    <thead style="background: #F3F4F6;">
                                                        <tr>
                                                            <th scope="col">{{ translate('Module') }}</th>
                                                            <th scope="col">{{ translate('Feature') }}</th>
                                                            <th scope="col">{{ translate('Show') }}</th>
                                                            <th scope="col">{{ translate('Create') }}</th>
                                                            <th scope="col">{{ translate('Edit') }}</th>
                                                            <th scope="col">{{ translate('Delete') }}</th>
                                                            <th scope="col">{{ translate('Manage') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @for ($i = 0; $i < sizeof($permissionModules); $i++)
                                                            @php
                                                                $permissions = getPermissionsOfModule($permissionModules[$i]->id);
                                                                $show_permission_id = hasPermissionInThisModule($permissionModules[$i]->id, translate('Show ') . ' ' . $permissionModules[$i]->module_name);
                                                                $create_permission_id = hasPermissionInThisModule($permissionModules[$i]->id, translate('Create ') . ' ' . $permissionModules[$i]->module_name);
                                                                $edit_permission_id = hasPermissionInThisModule($permissionModules[$i]->id, translate('Edit ') . ' ' . $permissionModules[$i]->module_name);
                                                                $delete_permission_id = hasPermissionInThisModule($permissionModules[$i]->id, translate('Delete ') . ' ' . $permissionModules[$i]->module_name);
                                                                $manage_permission_id = hasPermissionInThisModule($permissionModules[$i]->id, translate('Manage ') . ' ' . $permissionModules[$i]->module_name);
                                                            @endphp
                                                            <tr>
                                                                @if ($i == 0 || $permissionModules[$i]->parent_module != $permissionModules[$i - 1]->parent_module)
                                                                    <th>{{ $permissionModules[$i]->parent_module }}</th>
                                                                @else
                                                                    <th></th>
                                                                @endif
                                                                <td>{{ $permissionModules[$i]->module_name }}</td>
                                                                @if ($show_permission_id)
                                                                    <td>
                                                                        <label class="switch success small">
                                                                            <input type="checkbox"
                                                                                id="edtable_role_has_permissions_{{ $show_permission_id }}"
                                                                                onchange="setRemovePermissionsToRoleOnEdit('{{ $show_permission_id }}')">
                                                                            <span class="control"></span>
                                                                        </label>
                                                                    </td>
                                                                @else
                                                                    <td></td>
                                                                @endif
                                                                @if ($create_permission_id)
                                                                    <td>
                                                                        <label class="switch success small">
                                                                            <input type="checkbox"
                                                                                id="edtable_role_has_permissions_{{ $create_permission_id }}"
                                                                                onchange="setRemovePermissionsToRoleOnEdit('{{ $create_permission_id }}')">
                                                                            <span class="control"></span>
                                                                        </label>
                                                                    </td>
                                                                @else
                                                                    <td></td>
                                                                @endif
                                                                @if ($edit_permission_id)
                                                                    <td>
                                                                        <label class="switch success small">
                                                                            <input type="checkbox"
                                                                                id="edtable_role_has_permissions_{{ $edit_permission_id }}"
                                                                                onchange="setRemovePermissionsToRoleOnEdit('{{ $edit_permission_id }}')">
                                                                            <span class="control"></span>
                                                                        </label>
                                                                    </td>
                                                                @else
                                                                    <td></td>
                                                                @endif
                                                                @if ($delete_permission_id)
                                                                    <td>
                                                                        <label class="switch success small">
                                                                            <input type="checkbox"
                                                                                id="edtable_role_has_permissions_{{ $delete_permission_id }}"
                                                                                onchange="setRemovePermissionsToRoleOnEdit('{{ $delete_permission_id }}')">
                                                                            <span class="control"></span>
                                                                        </label>
                                                                    </td>
                                                                @else
                                                                    <td></td>
                                                                @endif
                                                                @if ($manage_permission_id)
                                                                    <td>
                                                                        <label class="switch success small">
                                                                            <input type="checkbox"
                                                                                id="edtable_role_has_permissions_{{ $manage_permission_id }}"
                                                                                onchange="setRemovePermissionsToRoleOnEdit('{{ $manage_permission_id }}')">
                                                                            <span class="control"></span>
                                                                        </label>
                                                                    </td>
                                                                @else
                                                                    <td></td>
                                                                @endif
                                                            </tr>
                                                        @endfor
                                                    </tbody>
                                                </table>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="col-md-12 text-right p-4">
                                            <button type="submit" class="btn long btn-orange"
                                                onclick="updateRole()">{{ translate('Update') }}</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /Update new role-->
                @endif
            </div>
        @endif

        <!--Delete Modal-->
        <div id="delete-modal" class="delete-modal modal fade show" aria-modal="true">
            <div class="modal-dialog modal-sm modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title h6">{{ translate('Delete Confirmation') }}</h4>
                    </div>
                    <div class="modal-body text-center">
                        <input type="hidden" name="role_id" id="role_id" value="">
                        <p class="mt-1">{{ translate('Are you sure to delete this') }}?</p>
                        <button type="button" class="btn btn-danger long mt-2"
                            data-dismiss="modal">{{ translate('cancel') }}</button>
                        <button type="submit" class="btn long mt-2"
                            onclick="deleteRole()">{{ translate('Delete') }}</button>
                    </div>
                </div>
            </div>
        </div>
        <!--Delete Modal-->
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

    <script type="application/javascript">
        let role_has_permissions = [];
        let role_has_module_permissions = [];
        let editable_role_has_permissions = [];

        (function($) {
            "use strict";
            $('#role_table').DataTable();
            hideElement(['#add_role_up_icon', '#update_role']);
        })(jQuery);
        

        /**
         * Toggle role adding form
         */
        function toggleRoleAddingForm() {
            "use strict";
            toggleElement([
                '#add_role',
                '#add_role_down_icon',
                '#add_role_up_icon'
            ]);
        }

        /**
         * hiding role updating form
         */
        function hideRoleUpdateForm() {
            "use strict";
            $('#update_role').hide();
        }

        /**
         * Show role editable form with necessary information
         */
        function showEditableForm(role_id) {
            "use strict";

            // console.log("roleId: ", role_id);
            flash()
            $('#update_role').show();
            $('#add_role').hide();
            // $.ajax({
            //     url: "{{ route('core.edit.role') }}",
            //     method: "GET",
            //     data: { id: role_id },
            //     dataType: 'json', // Explicitly expect JSON
            //     success: function(data, status, xhr) {
            //         console.log("=== SUCCESS ===");
            //         console.log("status: ", status);
            //         console.log("response: ", data);
            //         console.log("Raw response text:", xhr.responseText);
                    
            //         // Your code here - data is already parsed as JSON
            //         let response = data; // No need for JSON.parse(JSON.stringify(data))
            //         console.log("Role:", response.role);
            //         console.log("Permissions:", response.permissions);
            //         console.log("Modules:", response.modules);
            //     },
            //     error: function(xhr, status, error) {
            //         console.error("=== ERROR ===");
            //         console.error("Status:", status);
            //         console.error("Error:", error);
            //         console.error("Status Code:", xhr.status);
            //         console.error("Response Text:", xhr.responseText); // This will show you the HTML error
                    
            //         try {
            //             let error_response = JSON.parse(xhr.responseText);
            //             let error_message = error_response.message;
            //             toastr.error(error_message, "Error");
            //         } catch (e) {
            //             // If response is not JSON (it's HTML error page)
            //             console.error("Response is not JSON:", xhr.responseText);
            //             toastr.error("Server error occurred. Check console for details.", "Error");
            //         }
            //     }
            // });
            $.get("{{ route('core.edit.role') }}", {
                    id: role_id
                },
                function(data, status) {
                    let response = JSON.parse(JSON.stringify(data))
                    let role = response.role
                    let permissions = response.permissions
                    let modules = response.modules
                    let last_permission_id = {{ $last_permission_id }}
                    


                    for (let i = 0; i < modules.length; i++) {
                        if(modules[i].hasAllPermission == 1){
                            $('#editable_role_has_module_permissions_' + modules[i].id).prop('checked', true);
                        }
                        else{
                            $('#editable_role_has_module_permissions_' + modules[i].id).prop('checked', false);
                        }
                    }
                    for (let i = 1; i <= last_permission_id; i++) {
                        $('#edtable_role_has_permissions_' + i).prop('checked', false);
                    }
                    for (let i = 0; i < permissions.length; i++) {
                        $('#edtable_role_has_permissions_' + permissions[i]).prop('checked', true);
                        editable_role_has_permissions.push('' + permissions[i])
                    }
                    $('#role_name').val(role.name)
                    $('#role_id').val(role.id)
                }).fail(function(xhr, status, error) {
                let error_response = JSON.parse(xhr.responseText)
                let error_message = error_response.message
                toastr.error(error_message, "!");
            });
        }

        /**
         * Update role info 
         */
        function updateRole() {
            "use strict";
            let role_id = $('#role_id').val()
            let role_name = $('#role_name').val()
            let permissions = editable_role_has_permissions.join(',')

            $.post("{{ route('core.update.role') }}", {
                    _token: '{{ csrf_token() }}',
                    id: role_id,
                    role_name: role_name,
                    permissions: permissions
                },
                function(data, status) {
                    flash()
                    location.reload()
                    toastr.success("Role updated successfully", "Success!");
                }).fail(function(xhr, status, error) {
                let error_response = JSON.parse(xhr.responseText)
                let error_message = error_response.message
                let errors = {}

                if (error_response.hasOwnProperty('errors')) {
                    errors = objToArray(error_response.errors)
                    showFormErrorMessage(errors)
                } else {
                    toastr.error(error_message, "!");
                }
            });
        }

        /**
         * confirm before delete
         */
        function deleteConfirmation(role_id)
        {
            "use strict";
            $("#role_id").val(role_id);
            $('#delete-modal').modal('show');
        }

        /**
         * Delete role
         */
        function deleteRole() {
            "use strict";
            let role_id =  $("#role_id").val();
            $.post("{{ route('core.delete.role') }}", {
                    _token: '{{ csrf_token() }}',
                    id: role_id
                },
                function(data, status) {
                    flash()
                    location.reload()
                    toastr.success("Role deleted successfully", "Success!");
                }).fail(function(xhr, status, error) {
                let error_response = JSON.parse(xhr.responseText)
                let error_message = error_response.message
                toastr.error(error_message, "!");
            });
        }

        /**
         * set and remove permissions to role  
         */
        function setRemovePermissionsToRole(permission_id) {
            "use strict";
            if ($('#role_has_permissions_' + permission_id).is(":checked")) {
                role_has_permissions.push(''+permission_id)
            } else {
                let index = role_has_permissions.indexOf(''+permission_id);
                role_has_permissions.splice(index, 1);
            }
            $('#permissions').val(role_has_permissions.join(','))
        }
        
        /**
         * set and remove module permissions to role  
         */
        function setRemoveModulePermissionsToRole(module_id,permissionString) {
            "use strict";
            let permissions = JSON.parse(permissionString) 

            let last_permission_id = {{ $last_permission_id }}
            
            if ($('#role_has_module_permissions_' + module_id).is(":checked")) {
                for (let i = 0; i < permissions.length; i++) {
                    $('#role_has_permissions_' + permissions[i].id).prop('checked', true);
                    role_has_permissions.push('' + permissions[i].id)
                }
            }
            else{
                for (let i = 0; i < permissions.length; i++) {
                    $('#role_has_permissions_' + permissions[i].id).prop('checked', false);
                    let index = role_has_permissions.indexOf(''+permissions[i].id);
                    role_has_permissions.splice(index, 1);
                }
            }
            $('#permissions').val(role_has_permissions.join(','))
        }


        /**
         * set and remove permissions to role on edit 
         */
        function setRemovePermissionsToRoleOnEdit(permission_id) {
            "use strict";
            if ($('#edtable_role_has_permissions_' + permission_id).is(":checked")) {
                editable_role_has_permissions.push(''+permission_id)
            } else {
                let index = editable_role_has_permissions.indexOf(''+permission_id);
                editable_role_has_permissions.splice(index, 1);
            }
            $('#edditable_permissions').val(role_has_permissions.join(','))
        }

        /**
        * set and remove module permissions to role while editing  
        */
         function setRemoveModulePermissionsToRoleOnEdit(module_id,permissionString) {
            "use strict";
            let permissions = JSON.parse(permissionString) 
                       
            if ($('#editable_role_has_module_permissions_' + module_id).is(":checked")) {
                for (let i = 0; i < permissions.length; i++) {
                    $('#edtable_role_has_permissions_' + permissions[i].id).prop('checked', true);
                    editable_role_has_permissions.push('' + permissions[i].id)
                }
            }
            else{
                for (let i = 0; i < permissions.length; i++) {
                    $('#edtable_role_has_permissions_' + permissions[i].id).prop('checked', false);
                    let index = editable_role_has_permissions.indexOf(''+permissions[i].id);
                    if(index!=-1){
                        editable_role_has_permissions.splice(index, 1);
                    }
                }
            }
            $('#edditable_permissions').val(role_has_permissions.join(','))
        }

        /**
         * Flush data 
         */
        function flash() {
            "use strict";
            $('#role_name_update_error').html('')
            $('#permissions_update_error').html('')
            editable_role_has_permissions = []
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
.switch.success input:checked ~ .control {
    background-color: #ff5A1f !important;
    border-color: #ff8c00 !important;
}

/* 2. The sliding circle (the knob) */
.switch.success .control:after {
    background-color: #ffffff !important;
    border: 1px solid #e0e0e0;
    box-shadow: none !important;
}

/* 3. The knob position/border when checked */
.switch.success input:checked ~ .control:after {
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