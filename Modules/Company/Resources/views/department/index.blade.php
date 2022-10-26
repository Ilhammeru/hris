<table class="table align-middle table-row-dashed fs-6 gy-5 mb-0" id="table-department">
    <thead>
        <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
            <th>#</th>
            <th>Name</th>
            <th></th>
        </tr>
    </thead>
    <tbody></tbody>
</table>

@push('scripts')
    <script>
        let columns = [
                {data: 'id',
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    } 
                },
                {data: 'name', name: 'name'},
                {data: 'action', name: 'action'},
        ];
        let dt_route_department = "{{ route('company.department.ajax') }}";
        let dt_department = setDataTable('table-department', columns, dt_route_department);
    </script>
@endpush