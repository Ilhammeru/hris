@extends('recruitment::layouts.master')

@section('content')
    <div class="card">
        <div class="card-body">

            {{-- begin::tabs --}}
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                <button class="nav-link active" id="recruitment-step-tab" data-bs-toggle="tab" data-bs-target="#recruitment-step" type="button" role="tab" aria-controls="recruitment-step" aria-selected="true">{{ __('recruitment::view.recruitment_step') }}</button>
                </li>
                <li class="nav-item" role="presentation">
                <button class="nav-link" id="recruitment-notification-tab" data-bs-toggle="tab" onclick="openTab('recruitment-notification')" data-bs-target="#recruitment-notification" type="button" role="tab" aria-controls="recruitment-notification" aria-selected="false">{{ __('recruitment::view.recruitment_notification') }}</button>
                </li>
                <li class="nav-item" role="presentation">
                <button class="nav-link" id="messages-tab" data-bs-toggle="tab" data-bs-target="#messages" type="button" role="tab" aria-controls="messages" aria-selected="false">Messages</button>
                </li>
                <li class="nav-item" role="presentation">
                <button class="nav-link" id="settings-tab" data-bs-toggle="tab" data-bs-target="#settings" type="button" role="tab" aria-controls="settings" aria-selected="false">Settings</button>
                </li>
            </ul>
            
            <!-- Tab panes -->
            <div class="tab-content">
                <div class="tab-pane active" id="home" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
                    @include('recruitment::setting.partials.recruitment-step')
                </div>
                <div class="tab-pane" id="recruitment-notification" role="tabpanel" aria-labelledby="recruitment-notification-tab" tabindex="0">
                    @include('recruitment::setting.partials.recruitment-notification')
                </div>
                <div class="tab-pane" id="messages" role="tabpanel" aria-labelledby="messages-tab" tabindex="0">...</div>
                <div class="tab-pane" id="settings" role="tabpanel" aria-labelledby="settings-tab" tabindex="0">...</div>
            </div>
            {{-- end::tabs --}}
        </div>
    </div>

    {{-- begin::modal recruitment step --}}
    <div class="modal fade" id="modalNotificationStep" tabindex="-1" aria-labelledby="modalNotificationStepLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modal-title-notification-step"></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    {{-- end::modal recruitment step --}}
@endsection

@push('scripts')
    <script>
        let dt_route = "{{ route('employee.recruitment-setting.ajax') }}";

        let columns = [
                {data: 'id',
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    } 
                },
                {data: 'name', name: 'name'},
                {data: 'step', name: 'step'},
                {data: 'action', name: 'action'},
        ];
        let dt = setDataTable('table-recruitment-setting', columns, dt_route);


        $('#select-recruitment-step').select2();

        function openTab(id) {
            getRecruitmentStep();
        }

        function getRecruitmentStep() {
            $.ajax({
                type: 'GET',
                url: "{{ route('employee.recruitment-setting.get-recruitment-step') }}",
                beforeSend: function() {
                    
                },
                success: function(res) {
                    let data = res.data;
                    let option = '<option disabled selected>-- '+ "{{ __('view.choose') }}" +' --</option>';
                    for (let a = 0; a < data.length; a++) {
                        option += `<option value="${data[a].id}">${data[a].name}</option>`;
                    }
                    $('#select-recruitment-step').html(option);
                },
                error: function(err) {
                    setNotif(true, err.responseJSON);
                }
            })
        }

        function showNotificationSetup() {
            let val = $('#select-recruitment-step').val();
            let url = "{{ route('employee.recruitment-setting.get-notification-setup', ':id') }}";
            url = url.replace(':id', val);
            $.ajax({
                type: 'GET',
                url: url,
                beforeSend: function() {
                    
                },
                success: function(res) {
                    console.log(res);
                    if (res.data) {
                        $('#notif_type').val(res.data.notif_type);
                        var quill = new Quill('#quill-notif-message', {
                            placeholder: 'Message Text',
                            theme: 'snow'
                        });
                        quill.root.innerHTML = res.message;
                    }
                },
                error: function(err) {
                    console.log(err);
                    setNotif(true, err.responseJSON);
                }
            })
        }
    </script>
@endpush