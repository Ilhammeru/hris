@extends('employee::layouts.master')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/employee.css') }}">
@endpush

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="text-start">
                {!! set_link_text('<i class="bi bi-arrow-left p-0"></i>', route('employee.index'), 'button', 'secondary') !!}
            </div>

            {{-- divider --}}
            <div class="border-top mt-5"></div>

            <div class="main-profile mt-5">
                <div class="main-profile__header">
                    <div class="image-box">
                        <img src="{{ asset("images/150-2.jpg") }}" alt="profile-img">
                    </div>
                    <div class="profile-box w-100">
                        {{-- name and action button --}}
                        <div class="name-section d-flex align-items-center justify-content-between">
                            <div class="d-block">
                                {{-- name and location --}}
                                <p class="text-name fw-bold p-0 m-0">Ilham Meru Gumilang - 0112443</p>
                                <span><i class="bi bi-geo-alt"></i> Bandulan, Malang, Jawa Timur</span>
                                
                                {{-- detail job --}}
                                <div class="d-block mt-2">
                                    {{ __('employee::view.my_position', ['position' => 'Fullstack Developer']) }}
                                </div>
                            </div>

                            {{-- action button --}}
                            <div class="d-block">
                                <button class="btn btn-primary btn-edit-profile">{{ __('employee::view.edit_profile') }}</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="main-profile__sub-header mb-10">
                    <div class="d-flex align-items-center gap-10">
                        {{-- working time --}}
                        <div class="working-time d-flex align-items-center gap-2">
                            <i class="bi bi-clock"></i>
                            <div class="d-block">
                                <p class="fw-bold m-0 p-0">3+ Years</p>
                                <span>Working time</span>
                            </div>
                        </div>
                        {{-- warning letter --}}
                        <div class="warning-letter d-flex align-items-center gap-2">
                            <i class="bi bi-exclamation-diamond-fill"></i>
                            <div class="d-block">
                                <p class="fw-bold m-0 p-0">0 Mistakes</p>
                                <span>No Warning Letter</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- profile tabs --}}
                <div class="main-profile__tabs mt-5">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home-tab-pane" type="button" role="tab" aria-controls="home-tab-pane" aria-selected="true">{{ __('employee::view.general') }}</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-tab-pane" type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="false">{{ __('employee::view.experience') }}</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact-tab-pane" type="button" role="tab" aria-controls="contact-tab-pane" aria-selected="false">{{ __('employee::view.medical_record') }}</button>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
                            @include('employee::components.general_profile')
                        </div>
                        <div class="tab-pane fade" id="profile-tab-pane" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">
                            @include('employee::components.work_experience')
                        </div>
                        <div class="tab-pane fade" id="contact-tab-pane" role="tabpanel" aria-labelledby="contact-tab" tabindex="0">
                            @include('employee::components.medical_record')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $('.main-profile__tabs .nav-link').on("mouseleave", function() {
            $(this).css({
                'border': 'none'
            });
        })
    </script>
@endpush