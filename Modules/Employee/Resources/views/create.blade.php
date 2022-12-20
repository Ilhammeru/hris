@extends('employee::layouts.master', ['action_header' => true, 'btn_type' => 'button', 'onclick' => true, 'onclick_href' => 'save_employee', 'text' => __('employee::view.save_employee')])

@push('styles')
    <!-- the fileinput plugin styling CSS file -->
    <link href="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.5.2/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />
    <style>
        .avatar-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .avatar-wrapper .avatar-preview {
            position: relative;
        }
        .avatar-wrapper .avatar-preview img {
            height: 100px;
            width: 100px;
        }
        .avatar-wrapper .camera-btn {
            position: absolute;
            bottom: 0;
            right: 10px;
            z-index: 10;
        }
        .avatar-wrapper .camera-btn .bi {
            color: #000;
            font-size: 18px;
            cursor: pointer;
        }
        .social-media-input .icon {
            width: 50px;
            height: auto;
        }
        .fileinput-upload.fileinput-upload-button{
            display: none;
        }
    </style>
@endpush

@section('content')
    <form action="" id="form-add-employee">
        <div class="row">
            <div class="col-md-6">
                <div class="card card-body">
                    <p class="title text-uppercase mb-5 text-center fw-bold fs-2">{{ __('employee::view.personal_information') }}</p>

                    <!--begin::avatar-->
                    <div class="avatar-wrapper mb-5">
                        <div class="avatar-preview">
                            <img src="{{asset('images/blank.png')}}" class="rounded-circle preview-image" id="preview-image" alt="preview-image">
                            <!--camera button-->
                            <div class="camera-btn">
                                <input type="file" hidden id="avatar-file" name="avatar" onchange="changeFileAvatar(event)">
                                <i class="bi bi-camera-fill" onclick="openAvatarUploader()"></i>
                            </div>
                        </div>
                    </div>
                    <!--end::avatar-->

                    @include('employee::components.personal_info_form')

                   @include('employee::components.social_media_form')
                </div>
            </div>
            <div class="col-md-6">
                <div class="card card-body">
                    @include('employee::components.work_info_form')

                    <div class="form-group">
                        <div class="text-end">
                            <button class="btn btn-sm btn-success" id="btn-save" type="button" onclick="saveEmployee()">{{__('employee::view.save_employee')}}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.5.2/js/plugins/buffer.min.js" type="text/javascript"></script>
    <script src="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.5.2/js/plugins/filetype.min.js" type="text/javascript"></script>
    <!-- piexif.min.js is needed for auto orienting image files OR when restoring exif data in resized images and when you
        wish to resize images before upload. This must be loaded before fileinput.min.js -->
    <script src="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.5.2/js/plugins/piexif.min.js" type="text/javascript"></script>
    <!-- the main fileinput plugin script JS file -->
    <script src="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-fileinput@5.5.2/js/fileinput.min.js"></script>
    <script>
        $("#province").select2();
        $("#department").select2();

        $("#employement_status_file").fileinput();

        function openAvatarUploader() {
            $('#avatar-file').click();
        }

        /**
         * Preview image upload
         */
        function changeFileAvatar(e) {
            const preview = document.getElementById('preview-image');
            preview.src = URL.createObjectURL(e.target.files[0]);
            preview.onload = () => URL.revokeObjectURL(preview.src);
        }

        function getAddressAdditional(e, target) {
            let val = e.value;
            let url = '';
            let elem = '';
            if (target === 'city') {
                url = "{{route('employee.get-city')}}";
                elem = $('#city');
                if ($('#district').val()) {
                    $('#district').html('').prop("disabled", true);
                }
                if ($('#city').val()) {
                    $('#city').html('').prop('disabled', true);
                }
                if ($('#village').val()) {
                    $('#village').html('').prop('disabled', true);
                }
            } else if (target === 'district') {
                url = "{{route('employee.get-district')}}";
                elem = $('#district');
                if ($('#village').val()) {
                    $('#village').html('').prop('disabled', true);
                }
            } else if (target === 'village') {
                url = "{{route('employee.get-village')}}";
                elem = $('#village');
            }
            $.ajax({
                type: 'POST',
                url: url,
                data: {
                    'id': val
                },
                success: function(res) {
                    elem.html(buildSelectOption(res.data));
                    elem.select2();
                    elem.prop("disabled", false);
                }
            })
        }

        function getDivision(e) {
            let val = e.value;
            $.ajax({
                type: "POST",
                url: "{{route('employee.get-division')}}",
                data: {
                    department_id: val
                },
                success: function(res) {
                    $('#division').html(buildSelectOption(res.data));
                    $('#division').select2();
                    $('#division').prop('disabled', false);
                }
            })
        }

        function buildSelectOption(response) {
            let elem = `<option selected disabled>-- Choose --</option>`;
            for (let a = 0; a < response.length; a++) {
                elem += `<option value="${response[a].id}">${response[a].name}</option>`;
            }
            return elem;
        }

        function changeEmployementStatus(e) {
            let val = e.value;
            if (val == 1) {
                $('.internship-wrapper').addClass('d-none');
                $('.permanent-wrapper').removeClass('d-none');
                $('#internship_date').val('');
            } else {
                $('.internship-wrapper').removeClass('d-none');
                $('.permanent-wrapper').addClass('d-none');
                $('#permanent_date').val('');
            }
        }

        function saveEmployee() {
            let form = $('#form-add-employee');
            let data = new FormData(form[0]);
            let url = "{{route('employee.store')}}";
            $.ajax({
                type: "POST",
                url: url,
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                success: function(res) {
                    console.log(res);
                },
                error: function(err) {
                    console.error(err);
                }
            })
        }
    </script>
@endpush
