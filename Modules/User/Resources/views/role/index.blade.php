@extends('user::layouts.master')

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="text-end">
                <a class="btn btn-primary btn-sm" href="{{ route('user.role.create') }}">{{ __('user::roles.create_role') }}</a>
            </div>
            
            {{-- begin::table --}}
            <table class="table align-middle table-row-dashed fs-6 gy-5 mb-0" id="table-role">
                <thead>
                    <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                        <th>#</th>
                        <th>Name</th>
                        <th></th>
                    </tr>
                </thead>
            </table>
            {{-- end::table --}}
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let columns = [
                {data: 'id', width: '10%',
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    } 
                },
                {data: 'name', name: 'name', width: '80%'},
                {data: 'action', name: 'action', width: '10%'},
        ];
        let dt_route = "{{ route('user.role.ajax') }}";
        let dt = setDataTable('table-role', columns, dt_route);

        function deleteItem(id) {
            let url = `{{ route('user.role.delete', ':id') }}`;
            url = url.replace(':id', id);
            deleteMaster(
                `{{ __('user::roles.confirm_delete_text') }}`,
                `{{ __('user::roles.confirm_cancel_text') }}`,
                `{{ __('user::roles.confirm_delete_button') }}`,
                url,
                dt
            );
        }
    </script>
@endpush