@extends('user::layouts.master')

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="text-end">
                <a class="btn btn-primary btn-sm" href="{{ route('user.create') }}">{{ __('user::users.create_user') }}</a>
            </div>
            
            {{-- begin::table --}}
            <div class="table-responsive">
                <table class="table align-middle table-row-dashed fs-6 gy-5 mb-0" id="table-user">
                    <thead>
                        <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                            <th>#</th>
                            <th>Email</th>
                            <th>Role</th>
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
                {data: 'email', name: 'email'},
                {data: 'role', name: 'role'},
                {data: 'action', name: 'action'},
        ];
        let dt_route = "{{ route('user.ajax') }}";
        let dt = setDataTable('table-user', columns, dt_route);

        function deleteItem(id) {
            let url = `{{ route('user.delete', ':id') }}`;
            url = url.replace(':id', id);
            deleteMaster(
                `{{ __('user::users.confirm_delete_text') }}`,
                `{{ __('user::users.confirm_cancel_text') }}`,
                `{{ __('user::users.confirm_delete_button') }}`,
                url,
                dt
            );
        }
    </script>
@endpush