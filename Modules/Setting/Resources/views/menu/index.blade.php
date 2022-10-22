@php
    $ajax_url = route('setting.menu.ajax');
@endphp
@extends('setting::layouts.master')

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="text-end">
                <a class="btn btn-primary btn-sm" href="{{ route('setting.menu.create') }}">{{ __('setting::messages.create_menu') }}</a>
            </div>
            <table class="table align-middle table-row-dashed fs-6 gy-5 mb-0" id="table-menu">
                <thead>
                    <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                        <th>#</th>
                        <th>Name</th>
                        <th>Url</th>
                        <th>Parent</th>
                        <th>Icon</th>
                        <th></th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let table = $('#table-menu').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            scrollX: true,
            ajax: "{{ $ajax_url }}",
            columns: [
                {data: 'id', name: 'id'},
                {data: 'name', name: 'name'},
                {data: 'url', name: 'url'},
                {data: 'parent', name: 'parent'},
                {data: 'icon', name: 'icon'},
                {data: 'action', name: 'action'},
            ],
            order: [[0, 'desc']]
        })

        function deleteItem(id) {
            let url = `{{ route('setting.menu.delete', ':id') }}`;
            url = url.replace(':id', id);
            Swal.fire({
                title: `{{ __('setting::messages.confirm_delete_text') }}`,
                showCancelButton: true,
                cancelButtonText: `{{ __('setting::messages.confirm_cancel_text') }}`,
                confirmButtonText: `{{ __('setting::messages.confirm_delete_button') }}`,
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'delete',
                        url: url,
                        success: function(res) {
                            console.log(res);
                            setNotif(false, res.message);
                            table.ajax.reload();
                        },
                        error: function(err) {
                            setNotif(true, err.responseJSON);
                        }
                    })
                }
            })
        }
    </script>
@endpush