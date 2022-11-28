@extends('recruitment::layouts.master')

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table {{ dt_table_class() }}" id="table-applicant">
                    <thead class="{{ dt_head_class() }}">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>phone</th>
                            <th>address</th>
                            <th>CV</th>
                            <th></th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let dt_route = "{{ route('employee.recruitment.ajax.applicant', ':id') }}";
        dt_route = dt_route.replace(':id', "{{ $id }}");

        let columns = [
                {data: 'id',
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    } 
                },
                {data: 'fullname', name: 'fullname'},
                {data: 'email', name: 'email'},
                {data: 'phone', name: 'phone'},
                {data: 'address', name: 'address'},
                {data: 'cv', name: 'cv'},
                {data: 'action', name: 'action'},
        ];
        let dt = setDataTable('table-applicant', columns, dt_route);

        function acceptApplicant(id, vacancyId) {
            Swal.fire({
                title: "{{ __('recruitment::view.accept_applicant_confirmation') }}",
                showCancelButton: true,
                cancelButtonText: "{{ __('view.cancel') }}",
                confirmButtonText: "{{ __('view.confirm_submit') }}",
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'POST',
                        url: "{{ route('employee.recruitment.accept-applicant') }}",
                        data: {
                            id: id,
                            vacancy_id: vacancyId
                        },
                        success: function(res) {
                            setNotif(false, res.message);
                            dt.ajax.reload();
                            isSendNotifToApplicant(id);
                        },
                        error: function(err) {
                            setNotif(true, err.responseJSON == undefined ? err.responseText : err.responseJSON);
                        }
                    })
                }
            })
        }

        function isSendNotifToApplicant(id) {
            Swal.fire({
                title: "{{ __('recruitment::view.send_notif_confirmation') }}",
                showCancelButton: true,
                cancelButtonText: "{{ __('view.cancel') }}",
                confirmButtonText: "{{ __('view.send_notif_submit') }}",
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'POST',
                        url: "{{ route('employee.recruitment.send-notif-to-applicant') }}",
                        data: {
                            id: id,
                        },
                        success: function(res) {
                            setNotif(false, res.message);
                        },
                        error: function(err) {
                            setNotif(true, err.responseJSON == undefined ? err.responseText : err.responseJSON);
                        }
                    })
                } else {
                    
                }
            })
        }
    </script>
@endpush