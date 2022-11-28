@php
    use SimpleSoftwareIO\QrCode\Facades\QrCode;
@endphp

@extends('layouts.general-layout')

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
                    hello
                    {{-- begin::apply-card --}}
                    @include('recruitment::components.card-apply')
                    {{-- end::apply-card --}}

                    {{-- begin::pic-card --}}
                    @include('recruitment::components.card-pic')
                    {{-- end::pic-card --}}
                    
                </div>
            </div>
        </div>
    </div>
@endsection