@php
    use SimpleSoftwareIO\QrCode\Facades\QrCode;
@endphp

@extends('recruitment::layouts.master')

@push('styles')
    <style>
        .vacant-badge-information {
            padding: 3px 8px;
            background: #e2e5fe;
            color: #000;
            font-weight: bold;
            font-size: 10px;
            display: flex;
            align-items: center;
            border-radius: 20px;
        }

        .btn-publish-now {
            background: #3a36c6;
            color: #fff;
            font-weight: bold;
            font-size: 12px;
            width: 100%;
            margin-top: 15px;
        }

        .btn-publish-now:hover {
            color: #fff;
        }

        .btn-detail-applicant {
            width: 100%;
            background: #f4f6fe;
            color: #000;
            font-weight: bold;
            font-size: 12px;
            margin-top: 5px;
        }
    </style>
@endpush

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-9">
                    {{-- begin::header --}}
                    <div class="d-flex align-items-center justify-content-between mb-5">
                        <div>
                            <div class="d-flex align-items-center">
                                <p class="fs-1 fw-bold mb-1">{{ $data->title }}</p>
                                <i class="bi bi-check-circle-fill text-success d-none" id="verified-logo" style="line-height: 0; margin-left: 8px;"></i>
                            </div>
                            <div class="additional-information d-flex align-items-center">
                                <div class="vacant-badge-information">
                                    <i class="bi bi-bookmark" style="line-height: 0; margin-right: 8px;"></i>
                                    {{ $tags[0]->name }}
                                </div>
                                <div class="vacant-badge-information ms-4">
                                    <i class="bi bi-calendar-check" style="line-height: 0; margin-right: 8px;"></i>
                                    {{ $job_type[0]['name'] }}
                                </div>
                                <div class="vacant-badge-information ms-4">
                                    <i class="bi bi-person-workspace" style="line-height: 0; margin-right: 8px;"></i>
                                    {{ $work_type[0]['name'] }}
                                </div>
                            </div>
                        </div>
                        <div class="d-flex align-items-baseline">
                            <p class="m-0 p-0 me-5">Job updated: {{ $data->created_at->diffForHumans() }}</p>
                            {{-- <p class="m-0 p-0 ms-5">Job ID: {{ $data->id }}</p> --}}
                            {!!QrCode::format('svg')->generate($url_general)!!}
                        </div>
                    </div>
                    {{-- end::header --}}

                    {{-- begin::body --}}
                    <div class="vacant-body mt-5">
                        <div class="description mt-10">
                            {!! $data->description !!}
                        </div>
                    </div>
                    {{-- end::body --}}
                </div>
                <div class="col-md-3">

                    {{-- begin::apply-card --}}
                    {{-- @include('recruitment::components.card-apply') --}}
                    {{-- end::apply-card --}}

                    {{-- begin::pic-card --}}
                    {{-- @include('recruitment::components.card-pic') --}}
                    {{-- end::pic-card --}}

                    {{-- begin::vacant-info --}}
                    @include('recruitment::components.card-info')
                    {{-- end::vacant-info --}}

                    {{-- begin::publish button --}}
                    <div id="publish_container"></div>
                    {{-- end::publish button --}}

                    {{-- begin::detail-applicant-button --}}
                    <a class="btn btn-sm btn-detail-applicant" type="button" href="{{ route('employee.recruitment.detail-vacancy-applicant', $data->id) }}">{{ __('recruitment::view.detail_applicant') }} ({{ count($data->applicants) }})</a>
                    {{-- end::detail-applicant-button --}}
                    
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        init();

        function init(body = null) {
            let id = body == null ? "{{ $data->id }}" : body.id;
            let isActive = body == null ? "{{ $data->is_active }}" : body.is_active;
            let publishDate = body == null ? "{{ date('d M Y', strtotime($data->publish_date)) }}" : body.publish_date;
            let publishBy = body == null ? "{{ $publish_by }}" : body.publish;

            let payload = {
                id: id,
                isActive: isActive,
                publishDate: publishDate,
                publishBy: publishBy
            }

            buildPublishButton(payload);
            buildPublishInfo(payload);
        }

        function buildPublishInfo(payload) {
            $('#info-publish-by').text(payload.publishBy);
            $('#info-publish-date').text(payload.publishDate)
        }

        function buildPublishButton(payload) {
            let btn = '';
            if (payload.isActive == 1) {
                btn = `
                    <button class="btn-publish-now btn-success btn btn-sm d-flex align-items-center justify-content-center" type="button">
                        {{ __('recruitment::view.published') }}
                        <i class="bi bi-check text-white" style="line-height: 0; margin-left: 5px;"></i>
                    </button>
                `;
                $('#verified-logo').removeClass('d-none')
            } else {
                btn = `
                    <button class="btn-publish-now btn btn-sm" id="btn-publish-now" type="button" onclick="publishVacancy()">{{ __('recruitment::view.publish_now') }}</button>
                `;
                $('#verified-logo').addClass('d-none')
            }

            $('#publish_container').html(btn);
        }

        function publishVacancy() {
            let url = "{{ route('employee.recruitment.publish', ':id') }}";
            url = url.replace(':id', "{{ $data->id }}");
            $.ajax({
                type: 'GET',
                url: url,
                beforeSend: function() {
                    setLoading('btn-publish-now', true);
                },
                success: function(res) {
                    setLoading('btn-publish-now', false);
                    setNotif(false, res.message);
                    
                    init(res.data);
                },
                error: function(err) {
                    setLoading('btn-publish-now', false);
                    setNotif(true, err.responseJSON);
                }
            })
        }
    </script>
@endpush