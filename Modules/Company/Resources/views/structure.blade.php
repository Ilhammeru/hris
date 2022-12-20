@extends('company::layouts.master')

@section('content')
    <!-- begin::tabs -->
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="department-tab" data-bs-toggle="tab" data-bs-target="#department-tab-pane" type="button" role="tab" aria-controls="department-tab-pane" aria-selected="true">
                {{ __('company::company.department') }}
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="division-tab" data-bs-toggle="tab" data-bs-target="#division-tab-pane" type="button" role="tab" aria-controls="division-tab-pane" aria-selected="false">
                {{ __('company::company.division') }}
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="position-tab" data-bs-toggle="tab" data-bs-target="#position-tab-pane" type="button" role="tab" aria-controls="position-tab-pane" aria-selected="false">
                {{__('company::company.position')}}
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="emp-status-tab" data-bs-toggle="tab" data-bs-target="#emp-status-tab-pane" type="button" role="tab" aria-controls="emp-status-tab-pane" aria-selected="false">
                {{__('company::company.employee_status')}}
            </button>
        </li>
    </ul>
    <div class="tab-content" id="myTabContent">
        <!-- begin::department -->
        <div class="tab-pane fade show active" id="department-tab-pane" role="tabpanel" aria-labelledby="department-tab" tabindex="0">
            <div class="card card-body">
                @include('company::department.index')
            </div>
        </div>
        <!-- end::department -->

        <!-- begin::division -->
        <div class="tab-pane fade" id="division-tab-pane" role="tabpanel" aria-labelledby="division-tab" tabindex="0">
            <div class="card card-body">
                @include('company::division.index')
            </div>
        </div>
        <!-- end::division -->

        <div class="tab-pane fade" id="position-tab-pane" role="tabpanel" aria-labelledby="position-tab" tabindex="0">...</div>
        <div class="tab-pane fade" id="emp-status-tab-pane" role="tabpanel" aria-labelledby="emp-status-tab" tabindex="0">...</div>
    </div>
    <!-- end::tabs -->

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