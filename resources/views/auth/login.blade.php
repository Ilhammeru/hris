@extends('layouts.auth')

@section('content')
<!--begin::Content-->
<div class="d-flex flex-center flex-column flex-column-fluid p-10 pb-lg-20">
    <!--begin::Logo-->
    <a href="{{ route('dashboard') }}" class="mb-12">
        <img alt="Logo" src="{{ asset('images/logo-1.svg') }}" class="h-40px" />
    </a>
    <!--end::Logo-->
    <!--begin::Wrapper-->
    <div class="w-lg-500px bg-body rounded shadow-sm p-10 p-lg-15 mx-auto">
        <!--begin::Form-->
        <form class="form w-100" id="kt_sign_in_form" method="POST" action="{{ route('login.post') }}">
            @csrf
            <!--begin::Heading-->
            <div class="text-center mb-10">
                <!--begin::Title-->
                <h1 class="text-dark mb-3">Login</h1>
                <!--end::Title-->
                <!--begin::Link-->
                <div class="text-gray-400 fw-bold fs-4">Belum punya akun?
                <a href="{{ route('register') }}" class="link-primary fw-bolder">Daftar</a></div>
                <!--end::Link-->
            </div>
            <!--begin::Heading-->
            <!--begin::Input group-->
            <div class="fv-row mb-10">
                <!--begin::Label-->
                <label class="form-label fs-6 fw-bolder text-dark">Email</label>
                <!--end::Label-->
                <!--begin::Input-->
                <input class="form-control form-control-lg @error('email') is-invalid @enderror" value="{{ old('email') }}" type="text" id="username" name="email" autocomplete="off"/>
                <!--end::Input-->
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="fv-row mb-10">
                <!--begin::Wrapper-->
                <div class="d-flex flex-stack mb-2">
                    <!--begin::Label-->
                    <label class="form-label fw-bolder text-dark fs-6 mb-0">Password</label>
                    <!--end::Label-->
                    <!--begin::Link-->
                    <a href="{{ route('password.request') }}" class="link-primary fs-6 fw-bolder">Lupa Password ?</a>
                    <!--end::Link-->
                </div>
                <!--end::Wrapper-->
                <!--begin::Input-->
                <input class="form-control form-control-lg " type="password" id="password" name="password" autocomplete="off" />
                <!--end::Input-->
            </div>
            <!--end::Input group-->
            <!--begin::Actions-->
            <div class="text-center">
                <!--begin::Submit button-->
                <button type="submit" id="kt_sign_in_submit" class="btn btn-lg btn-primary w-100 mb-5">
                    <span class="indicator-label">Login</span>
                    <span class="indicator-progress">Tunggu...
                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                </button>
                <!--end::Submit button-->
            </div>
            <!--end::Actions-->
        </form>
        <!--end::Form-->
    </div>
    <!--end::Wrapper-->
</div>
<!--end::Content-->
@endsection
