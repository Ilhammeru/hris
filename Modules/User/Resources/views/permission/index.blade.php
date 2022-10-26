@extends('user::layouts.master')

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="text-end">
                <a class="btn btn-primary btn-sm" href="{{ route('user.permission.create') }}">{{ __('user::permissions.create_permission') }}</a>
            </div>
            
            {{-- begin::table --}}
            <div class="table-responsive">
                <table class="table align-middle table-row-dashed fs-6 gy-5 mb-0" id="table-permission">
                    <thead>
                        <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                            <th>#</th>
                            <th>Name</th>
                            <th>Group</th>
                            <th></th>
                        </tr>
                    </thead>
                </table>
            </div>
            {{-- end::table --}}
        </div>
    </div>
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
                {data: 'group', name: 'group'},
                {data: 'action', name: 'action'},
        ];
        let dt_route = "{{ route('user.permission.ajax') }}";
        let dt = setDataTable('table-permission', columns, dt_route);

        function deleteItem(id) {
            let url = `{{ route('user.permission.delete', ':id') }}`;
            url = url.replace(':id', id);
            deleteMaster(
                `{{ __('user::permissions.confirm_delete_text') }}`,
                `{{ __('user::permissions.confirm_cancel_text') }}`,
                `{{ __('user::permissions.confirm_delete_button') }}`,
                url,
                dt
            );
        }
    </script>
@endpush