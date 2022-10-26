@extends('company::layouts.master')

@section('content')
    {{-- begin::collapse-department --}}
    <div class="card card-body" data-bs-toggle="collapse" href="#collapseDepartment" role="button" aria-expanded="false" aria-controls="collapseDepartment">
        <div class="d-flex align-items-center justify-content-between">
            <h3 class="mb-0 pb-0">
                <b>{{ __('company::company.department') }}</b>
            </h3>
            <button class="btn btn-sm btn-primary"
                type="button"
                onclick="createDept()">
                {{ __('company::company.create_department') }}
            </button>
        </div>
    </div>
    <div class="collapse show mb-5" id="collapseDepartment">
        <div class="card card-body">
            @include('company::department.index')
        </div>
    </div>
    {{-- end::collapse-department --}}

    {{-- begin::collapse-division --}}
    <div class="card card-body mt-5" data-bs-toggle="collapse" href="#collapseDivision" role="button" aria-expanded="false" aria-controls="collapseDivision">
        <div class="d-flex align-items-center justify-content-between">
            <h3>
                <b>{{ __('company::company.division') }}</b>
            </h3>
            <button class="btn btn-sm btn-primary"
                type="button"
                onclick="createDiv()">
                {{ __('company::company.create_division') }}
            </button>
        </div>
    </div>
    <div class="collapse show" id="collapseDivision">
        <div class="card card-body">
            @include('company::division.index')
        </div>
    </div>
    {{-- end::collapse-division --}}

    <!-- begin::modal cretea -->
    <div class="modal fade" id="modal-add-structure" tabindex="-1" aria-labelledby="modal-add-structureLabel" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content">
            <form id="form-structure">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modal-title"></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modal-structure-body">
                    
                </div>
                <div class="modal-footer">
                    <button type="button" id="btn-save-structure" class="btn btn-primary btn-sm" onclick="save()">{{ __('company::company.save') }}</button>
                </div>
            </form>
        </div>
        </div>
    </div>
    <!-- begin::modal cretea -->
@endsection

@push('scripts')
    <script>
        const modalBody = $('#modal-structure-body');
        const modalTitle = $('#modal-title');
        const modal = $('#modal-add-structure');
        const form = $('#form-structure');

        function createDept() {
            $.ajax({
                type: 'GET',
                url: "{{ route('company.department.create') }}",
                success: function(res) {
                    modalBody.html(res.body);
                    modal.modal('show');
                    modalTitle.text("{{ __('company::company.create_department') }}");

                    form.attr('action', "{{ route('company.department.store') }}");
                    form.attr('method', 'POST');
                },
                error: function(err) {
                    setNotif(true, err.responseJSON);
                }
            })
        }

        function createDiv() {
            $.ajax({
                type: 'GET',
                url: "{{ route('company.division.create') }}",
                success: function(res) {
                    modalBody.html(res.body);
                    modal.modal('show');
                    modalTitle.text("{{ __('company::company.create_division') }}");

                    form.attr('action', "{{ route('company.division.store') }}");
                    form.attr('method', 'POST');
                    $('#department_div').select2();
                },
                error: function(err) {
                    setNotif(true, err.responseJSON ? err.responseJSON : err.responseText);
                }
            })
        }

        function editDept(id) {
            let url = "{{ route('company.department.edit', ":id") }}";
            url = url.replace(':id', id);
            $.ajax({
                type: 'GET',
                url: url,
                data: {
                    id: id
                },
                beforeSend: function() {
                    
                },
                success: function(res) {
                    let urlUpdateDept = "{{ route('company.department.update', ':id') }}";
                    urlUpdateDept = urlUpdateDept.replace(':id', id);
                    modalTitle.text("{{ __('company::company.edit_department') }}");
                    modalBody.html(res.body);
                    modal.modal('show');
                    form.attr('action', urlUpdateDept);
                    form.attr('method', 'PATCH');
                },
                error: function(err) {
                    setNotif(true, err.responseJSON);
                }
            })
        }

        function editDiv(id) {
            let url = `{{ route('company.division.edit', ':id') }}`;
            url = url.replace(':id', id);
            $.ajax({
                type: 'GET',
                url: url,
                data: {
                    id: id
                },
                beforeSend: function() {
                    
                },
                success: function(res) {
                    let urlUpdateDiv = "{{ route('company.division.update', ':id') }}";
                    urlUpdateDiv = urlUpdateDiv.replace(':id', id);
                    modalTitle.text("{{ __('company::company.edit_division') }}");
                    modalBody.html(res.body);
                    modal.modal('show');
                    form.attr('action', urlUpdateDiv);
                    form.attr('method', 'PATCH');

                    $('#department_div').select2();
                },
                error: function(err) {
                    setNotif(true, err.responseJSON);
                }
            })
        }

        function save() {
            let data = $('#form-structure').serialize();
            let url = $('#form-structure').attr('action');
            let method = $('#form-structure').attr('method');

            $.ajax({
                type: method,
                url: url,
                data: data,
                beforeSend: function() {
                    setLoading('btn-save-structure', true);
                },
                success: function(res) {
                    setLoading('btn-save-structure', false);
                    setNotif(false, res.message);
                    if (res.type == 'department') {
                        dt_department.ajax.reload();
                        $('#collapseDepartment').collapse('show');
                    } else if (res.type == 'division') {
                        dt_division.ajax.reload();
                        $('#collapseDivision').collapse('show');
                    }
                    $('#modal-add-structure').modal('hide');
                    resetForm('form-structure');
                },
                error: function(err) {
                    setLoading('btn-save-structure', false);
                    setNotif(true, err.responseJSON);
                }
            })
        }

        async function deleteDept(id) {
            let urlDelete = "{{ route('company.department.delete', ':id') }}";
            urlDelete = urlDelete.replace(':id', id);
            await deleteMaster(
                `{{ __('company::company.confirm_delete_text_department') }}`,
                `{{ __('user::permissions.confirm_cancel_text') }}`,
                `{{ __('user::permissions.confirm_delete_button') }}`,
                urlDelete,
                dt_department
            );
            dt_division.ajax.reload();
        }

        async function deleteDiv(id) {
            let urlDelete = "{{ route('company.division.delete', ':id') }}";
            urlDelete = urlDelete.replace(':id', id);
            await deleteMaster(
                `{{ __('company::company.confirm_delete_text_division') }}`,
                `{{ __('user::permissions.confirm_cancel_text') }}`,
                `{{ __('user::permissions.confirm_delete_button') }}`,
                urlDelete,
                dt_division
            );
        }
    </script>
@endpush