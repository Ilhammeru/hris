@extends('user::layouts.master')

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-5">
                        <p class="mb-0">
                            {{ __('user::permissions.index_title') }}
                        </p>
                    </div>

                    {{-- begin::form --}}
                    <form action="{{ route('user.permission.store') }}" method="POST" id="form-permission">
                        {{-- begin::form permission --}}
                        @include('user::permission.form-permission')
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
                    setLoading('btn-save-permission', false);
                    setNotif(false, res);
                    resetForm('form-permission');
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

        function getPermissionGroupValue() {
            $.ajax({
                type: 'GET',
                url: "{{ route('user.permission-group.list') }}",
                success: function(res) {
                    let opt = "";
                    for(let a = 0; a < res.length; a++) {
                        opt += `<option value="${res[a].id}">${res[a].name}</option>`;
                    }
                    $('#permission_group').html(opt);
                    $('#permission_group').select2();
                }
            })
        }
    </script>
@endpush