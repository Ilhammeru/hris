@php
    $permission = $data ?? null;
@endphp
@extends('user::layouts.master')

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="text-start mt-3 mb-3">
                        <a href="{{ route('user.permission') }}" class="btn btn-secondary btn-sm">{{ __('user::permissions.back') }}</a>
                    </div>

                    <div class="d-flex align-items-center justify-content-between mb-5">
                        <h3 class="mb-0">
                            {{ __('user::permissions.index_title') }}
                        </h3>
                    </div>

                    {{-- begin::form --}}
                    <form action="{{ $permission ? route('user.permission.update', $permission->id) : route('user.permission.store') }}" method="{{ $permission ? 'PATCH' : 'POST' }}" id="form-permission">
                        {{-- begin::form permission --}}
                        @include('user::permission.form-permission', ['data' => $permission])
                        {{-- end::form permission --}}

                        {{-- begin::form-action --}}
                        <div class="text-start">
                            <button class="btn btn-sm btn-primary" type="button" onclick="save()" id="btn-save-permission">
                                {{ __('user::permissions.form_save') }}
                            </button>
                        </div>
                        {{-- end::form-action --}}
                    </form>
                    {{-- end::form --}}
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-5">
                        <p class="mb-0">
                            {{ __('user::permissions.permission_group') }}
                        </p>
                        <button class="btn btn-primary btn-sm"
                            data-bs-toggle="modal"
                            data-bs-target="#modalCreateGroup">
                            {{ __('user::permissions.create_permission_group') }}
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-6 gy-5 mb-0" id="table-permission-group">
                            <thead>
                                <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                                    <th>#</th>
                                    <th>Name</th>
                                    <th></th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- begin::modal create --}}
    <div class="modal fade" id="modalCreateGroup" tabindex="-1" aria-labelledby="modalCreateGroupLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalCreateGroupLabel">Create Group</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('user.permission-group.store') }}" id="form-permission-group" method="POST">
                        @include('user::permission.form-permission-group')
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary btn-sm" id="btn-save-group" onclick="saveGroup()">{{ __('user::permissions.form_save') }}</button>
                </div>
            </div>
        </div>
    </div>
    {{-- end::modal create --}}
@endsection

@push('scripts')
    <script>
        'use strict'
        let columns = [
                {data: 'id',
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    } 
                },
                {data: 'name', name: 'name'},
                {data: 'action', name: 'action', sortable: false},
        ];
        let dt_route = "{{ route('user.permission-group') }}";
        let dt_group = setDataTable('table-permission-group', columns, dt_route);

        getPermissionGroupValue();

        function save() {
            let data = $('#form-permission').serialize();
            let url = $('#form-permission').attr('action');
            let method = $('#form-permission').attr('method');

            $.ajax({
                type: method,
                url: url,
                data: data,
                beforeSend: function() {
                    setLoading('btn-save-permission', true);
                },
                success: function(res) {
                    console.log(res);
                    setLoading('btn-save-permission', false);
                    setNotif(false, res);
                    @if(!$permission)
                        resetForm('form-permission');
                    @endif
                },
                error: function(err) {
                    setLoading('btn-save-permission', false);
                    setNotif(true, err.responseJSON);
                }
            })
        }

        function saveGroup() {
            let data = $('#form-permission-group').serialize();
            let url = $('#form-permission-group').attr('action');
            let method = $('#form-permission-group').attr('method');

            $.ajax({
                type: method,
                url: url,
                data: data,
                beforeSend: function() {
                    setLoading('btn-save-group', true);
                },
                success: function(res) {
                    setLoading('btn-save-group', false);
                    setNotif(false, res);
                    resetForm('form-permission-group');
                    $('#modalCreateGroup').modal('hide');
                    dt_group.ajax.reload();
                    getPermissionGroupValue();
                },
                error: function(err) {
                    setLoading('btn-save-group', false);
                    setNotif(true, err.responseJSON);
                }
            })
        }

        function editGroup(id) {
            let url = "{{ route('user.permission-group.edit', ':id') }}";
            url = url.replace(':id', id);
            $.ajax({
                type: 'GET',
                url: url,
                success: function(res) {
                    $('#modalCreateGroup').modal('show');
                    $('#name-group').val(res.name);

                    // edit url form
                    let urlUpdate = "{{ route('user.permission-group.update', ':id') }}";
                    urlUpdate = urlUpdate.replace(':id', id);
                    $('#form-permission-group').attr('action', urlUpdate);
                    $('#form-permission-group').attr('method', 'PATCH');

                    // set title
                    $('#modalCreateGroupLabel').text('Update Group');
                },
                error: function(err) {
                    setNotif(true, err.responseJSON);
                }
            })
        }

        function getPermissionGroupValue() {
            $.ajax({
                type: 'GET',
                url: "{{ route('user.permission-group.list') }}",
                success: function(res) {
                    let opt = "";
                    for(let a = 0; a < res.length; a++) {
                        @if($permission)
                            let selected = "{{ $permission->permission_group_id }}";
                            let select = '';
                            if (selected == res[a].id) {
                                select = 'selected';
                            }
                            opt += `<option value="${res[a].id}" ${select}>${res[a].name}</option>`;
                        @else
                            opt += `<option value="${res[a].id}">${res[a].name}</option>`;
                        @endif
                    }
                    $('#permission_group').html(opt);
                    $('#permission_group').select2();
                }
            });
        }

        function deleteItem(id) {
            let url = `{{ route('user.permission-group.delete', ':id') }}`;
            url = url.replace(':id', id);
            deleteMaster(
                `{{ __('user::permissions.confirm_delete_text_group') }}`,
                `{{ __('user::permissions.confirm_cancel_text') }}`,
                `{{ __('user::permissions.confirm_delete_button') }}`,
                url,
                dt_group
            );
            dt_group.ajax.reload();
            getPermissionGroupValue();
        }
    </script>
@endpush