@extends('employee::layouts.master')

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="text-end">
                <a href="{{ route('employee.create') }}" class="btn btn-primary">{{ __('employee::view.add_employee') }}</a>
            </div>

            {{-- divider --}}
            <div class="border-top mt-5"></div>

            <div class="table-responsive mt-5">
                <table class="table {{ dt_table_class() }}" id="table-employee-list">
                    <thead class="{{ dt_head_class() }}">
                        <tr>
                            <th>#</th>
                            <th>{{ __("employee::view.name") }}</th>
                            <th>{{ __('employee::view.division') }}</th>
                            <th>{{ __('employee::view.employement_status') }}</th>
                            <th>{{ __('employee::view.work_time') }}</th>
                            <th>{{ __('employee::view.action') }}</th>
                        </tr>
                    </thead>
                </table>
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
                {data: 'name', name: 'name'},
                {data: 'division_id', name: 'division_id'},
                {data: 'status', name: 'status'},
                {data: 'working_time', name: 'working_time'},
                {data: 'action', name: 'action'},
        ];
        let dt_route = "{{ route('employee.index.ajax') }}";
        let dt = setDataTable('table-employee-list', columns, dt_route);

        // $('#table-employee-list tbody').on('click', 'tr', function () {
        //     $(this).toggleClass('selected');
        // });
    </script>
@endpush
