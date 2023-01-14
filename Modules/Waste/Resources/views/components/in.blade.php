@php
    $ajax_url = route('waste.ajax.in');
@endphp

<div class="row">
    <div class="col-md-3 col-sm-12">
        <div class="pe-5">
            <p class="mb-2 fw-bolder text-center">Filter</p>
            @include('waste::components.in_filter')
        </div>
    </div>
    <div class="col-md-9 col-sm-12">
        <table class="table align-middle table-row-dashed fs-6 gy-5 mb-0" id="table-menu">
            <thead>
                <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                    <th>Number</th>
                    <th>Type</th>
                    <th>Date In</th>
                    <th>Source</th>
                    <th>Qty</th>
                    <th>Exp</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

@push('scripts')
    <script>
        let tableIn;
        initTableIn();

        function initTableIn(param = {}) {
            if (param) {
                $('#table-menu').DataTable().destroy();
            }
            tableIn = $('#table-menu').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                scrollX: true,
                ajax: {
                    url: "{{ $ajax_url }}",
                    data: param,
                },
                columns: [
                    {data: 'code_number', name: 'code_number'},
                    {data: 'type', name: 'type'},
                    {data: 'date', name: 'date'},
                    {data: 'waste_source', name: 'waste_source'},
                    {data: 'qty', name: 'qty'},
                    {data: 'exp', name: 'exp'},
                ],
                order: [[0, 'desc']]
            });
        }

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

        function filterIn() {
            let data = $('#form-filter-in').serialize();
            // initTableIn(data);
            let param = JSON.parse('{"' + decodeURI(data).replace(/"/g, '\\"').replace(/&/g, '","').replace(/=/g,'":"') + '"}');
            let elems = $('.individual-code');
            let selected = [];
            for (let a = 0; a < elems.length; a++) {
                let ids = elems[a].id;
                let check = $('#' + ids).prop('checked');
                if (check) {
                    selected.push($('#' + ids).val());
                }
            }
            param.codes = selected;
            initTableIn(param);
            $('#cancel-filter').removeClass('d-none');
        }
        
        function clearFilter() {
            document.getElementById('form-filter-in').reset();;
            initTableIn();
            $('#cancel-filter').addClass('d-none');
        }
    </script>
@endpush