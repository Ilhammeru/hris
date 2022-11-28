@extends('recruitment::layouts.master')

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="text-end">
                <a href="{{ route('employee.recruitment.create') }}" class="btn btn-primary">{{ __('recruitment::view.create_recruitment') }}</a>
            </div>

            {{-- divider --}}
            <div class="border-top mt-5"></div>

            <div class="mt-5">
                <div class="table-responsive">
                    <table class="table align-middle table-row-dashed fs-6 gy-5 mb-0" id="table-vacancy">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ __('recruitment::view.title') }}</th>
                                <th>{{ __('recruitment::view.department') }}</th>
                                <th>{{ __('recruitment::view.division') }}</th>
                                <th>{{ __('recruitment::view.start_date') }}</th>
                                <th>{{ __('recruitment::view.end_date') }}</th>
                                <th>{{ __('view.created_by') }}</th>
                                <th>{{ __('view.action') }}</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
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
                {data: 'title', name: 'title'},
                {data: 'department_id', name: 'department_id'},
                {data: 'division_id', name: 'division_id'},
                {data: 'start_date', name: 'start_date'},
                {data: 'end_date', name: 'end_date'},
                {data: 'created_by', name: 'created_by'},
                {data: 'action', name: 'action'},
        ];
        let dt_route = "{{ route('employee.recruitment.ajax') }}";
        let dt = setDataTable('table-vacancy', columns, dt_route);

        function deleteItem(id) {
            let url = `{{ route('employee.recruitment.delete', ':id') }}`;
            url = url.replace(':id', id);
            deleteMaster(
                `{{ __('recruitment::view.confirm_delete_text') }}`,
                `{{ __('recruitment::view.confirm_cancel_text') }}`,
                `{{ __('recruitment::view.confirm_delete_button') }}`,
                url,
                dt
            );
        }
    </script>
@endpush
