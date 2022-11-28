@extends('layouts.general-layout')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-body">
                <h2 class="text-center">Tell us about yourself</h2>
                <form action="{{ route('employee.recruitment.apply-job', $id) }}" action="POST" id="form-apply" enctype="multipart/form-data">
                    <div class="row mt-10">
                        <div class="col-md-6 pe-10 border-end">
                            <h5 class="text-center fw-bold mb-5">{{ __('recruitment::view.general_data') }}</h5>
                            @include('recruitment::components.form-applicant')
                        </div>
                        <div class="col-md-6 ps-10">
                            <h5 class="text-center fw-bold mb-5">{{ __('recruitment::view.supporting_document') }}</h5>
                            @include('recruitment::components.form-supporting')
    
                            <div class="text-end">
                                <button type="button" id="btn-apply-job" onclick="applyJob()" class="btn btn-sm btn-primary text-end">{{ __('view.submit') }}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $('#province').select2();
        $('#city').select2();
        $('#district').select2();
        $('#village').select2();

        function applyJob() {
            let form = $('#form-apply');
            let url = form.attr('action');
            let method = form.attr('method');

            let formWithData = $('#form-apply')[0];
            let data = new FormData(formWithData);
            $.ajax({
                type: "POST",
                url: url,
                processData: false,
                contentType: false,
                cache: false,
                data: data,
                beforeSend: function() {
                    let loading = `<div class="spinner-border" style="width: 1em; height: 1em" role="status">
                        <span class="visually-hidden"></span>
                        </div>`;
                    $('#btn-apply-job').html(loading);
                },
                success: function(res) {
                    $('#btn-apply-job').html("{{ __('view.submit') }}");
                    window.location.href = "{{ route('apply-success') }}"
                },
                error: function(err) {
                    $('#btn-apply-job').html("{{ __('view.submit') }}");
                    setNotif(true, err.responseJSON);
                }
            })
        }

        function getCity() {
            let province = $('#province').val();

            $.ajax({
                type: 'POST',
                url: "{{ route('get-city-by-province') }}",
                data: {
                    province: province
                },
                beforeSend: function() {
                    
                },
                success: function(res) {
                    let city = res.data.cities
                    let option = '<option disabled selected>-- Choose --</option>';
                    for (let a = 0; a < city.length; a++) {
                        option += `<option value="${city[a].id}">${city[a].name}</option>`;
                    }
                    $('#city').html(option);
                    $('#city').select2();
                }
            })
        }

        function getDistrict() {
            let city = $('#city').val();

            $.ajax({
                type: 'POST',
                url: "{{ route('get-district-by-city') }}",
                data: {
                    city: city
                },
                beforeSend: function() {
                    
                },
                success: function(res) {
                    let districts = res.data.districts
                    let option = '<option disabled selected>-- Choose --</option>';
                    for (let a = 0; a < districts.length; a++) {
                        option += `<option value="${districts[a].id}">${districts[a].name}</option>`;
                    }
                    $('#district').html(option);
                    $('#district').select2();
                },
            })
        }

        function getVillage() {
            let district = $('#district').val();

            $.ajax({
                type: 'POST',
                url: "{{ route('get-village-by-district') }}",
                data: {
                    district: district
                },
                beforeSend: function() {
                    
                },
                success: function(res) {
                    let villages = res.data.villages
                    let option = '<option disabled selected>-- Choose --</option>';
                    for (let a = 0; a < villages.length; a++) {
                        option += `<option value="${villages[a].id}">${villages[a].name}</option>`;
                    }
                    $('#village').html(option);
                    $('#village').select2();
                },
            })
        }
    </script>
@endpush