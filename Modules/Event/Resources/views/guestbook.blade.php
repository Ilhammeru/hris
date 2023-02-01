<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@lang('event::view.guestbook')</title>

	<!-- Theme included stylesheets -->
	<link href="//cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
	<link href="//cdn.quilljs.com/1.3.6/quill.bubble.css" rel="stylesheet">

	{{-- date range picker --}}
	<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    <!--begin::Fonts-->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
    <!--end::Fonts-->
    <!--begin::Global Stylesheets Bundle(used by all pages)-->
    <link href="{{ asset('css/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
    <!--end::Global Stylesheets Bundle-->
	<!--begin::Page Vendor Stylesheets(used by this page)-->
	<link href="{{ asset('css/datatables.min.css') }}" rel="stylesheet" type="text/css" />
	{{-- <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet" type="text/css" /> --}}
	<!--end::Page Vendor Stylesheets-->

	{{-- bootstrap icon --}}
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">

	{{-- global css --}}
	<link rel="stylesheet" href="{{ asset('css/global.scss') }}">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
	{{-- less --}}
	<script>
		less = {
		  env: "development",
		  async: false,
		  fileAsync: false,
		  poll: 1000,
		  functions: {},
		  dumpLineNumbers: "comments",
		};
	</script>
	<script src="{{ asset('plugins/less/dist/less.min.js') }}"></script>

	{{-- custom style --}}
	<style>
		.search-guestbook-container {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
        }
        .search-item {
            width: 500px;
        }
        .search-group {
            display: flex;
            align-items: center;
        }
        .search-group .form-control {
            border-top-right-radius: 0 !important;
            border-bottom-right-radius: 0 !important;
        }
        #btn-search {
            border-top-left-radius: 0 !important;
            border-bottom-left-radius: 0 !important;
        }
        .search-guestbook-container .title {
            font-size: 32px;
            font-weight: bold;
            letter-spacing: 5px;
            text-shadow: -1px -1px 0px lightblue,
                3px 3px 0px lightblue,
                6px 6px 0px lightblue;
        }
        .title-sign {
            font-weight: bold;
            font-size: 20px;
            margin-top: 10px;
        }
        #kt_wrapper {
            padding-left: 0 !important;
            padding-top: 0 !important;
        }
        .ui-autocomplete {
            max-height: 100px;
            overflow-y: auto;
            /* prevent horizontal scrollbar */
            overflow-x: hidden;
        }
        .wrapper-signature {
            position: relative;
            width: 100%;
            height: 200px;
            -moz-user-select: none;
            -webkit-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        .signature-pad {
            position: absolute;
            left: 0;
            top: 0;
            width:100%;
            height:200px;
            background-color: #fff;
        }
        #signature-pad {
            border: 1px solid #e6e6e6;
        }
	</style>

    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
</head>
<body id="kt_body" class="header-tablet-and-mobile-fixed aside-enabled">
    <!--begin::Main-->
		<!--begin::Root-->
		<div class="d-flex flex-column flex-root">
			<!--begin::Page-->
			<div class="page d-flex flex-row flex-column-fluid">
				<!--begin::Wrapper-->
				<div class="wrapper d-flex flex-column flex-row-fluid" id="kt_wrapper">
					@include('partials.header', [
                        'pageTitle' => __('event::view.guestbook'),
                        'has_action_header' => false,
                        'btn_type' => '',
                        'text' => '',
                        'onclick' => '',
                        'onclick_href' => ''
                    ])

					<!--begin::Content-->
					<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
						<!--begin::Post-->
						<div class="post d-flex flex-column-fluid" id="kt_post">
							<!--begin::Container-->
							<div id="kt_content_container" class="container-xxl">

								<div class="search-guestbook-container">
                                    <div class="text-center search-item">
                                        <p class="title text-center">{{ $data->name }}</p>
                                        <form action="" id="form-check-in">
                                            <div class="search-group">
                                                {{-- <input type="text" class="form-control w-100" name="search" id="search" oninput="updateButton(this)"> --}}
                                                <select name="attendant_id" id="search-attend" class="form-control form-select">
                                                    <option value=""></option>
                                                    @foreach ($employees as $item)
                                                        <option value="{{ $item['id'] }}">{{ $item['name'] }} ({{ $item['employee_id'] }})</option>
                                                    @endforeach
                                                </select>
                                                {{-- <input type="hidden" name="attendant_id" value="" id="attendant_id"> --}}
                                                <input type="hidden" name="event_id" value="{{ $data->id }}" id="event_id">
                                                <input type="hidden" name="signature" id="signature_field">
                                                <button class="btn btn-primary" type="button" id="btn-search"
                                                    data-option="{{ $data->option_finisher }}"
                                                    onclick="searchData({{ $data->option_finisher }})">
                                                    <i class="fa fa-sign-in-alt"></i> 
                                                </button>
                                            </div>
                                            <div class="form-group mt-3">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="vaccine" id="vaccine1" checked value="moderna">
                                                    <label class="form-check-label" for="vaccine1">Moderna</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="vaccine" id="vaccine2" value="pfizer">
                                                    <label class="form-check-label" for="vaccine2">Pfizer</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="vaccine" id="vaccine3" value="sinovac">
                                                    <label class="form-check-label" for="vaccine3">Sinovac</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="vaccine" id="vaccine4" value="astra">
                                                    <label class="form-check-label" for="vaccine4">Astra</label>
                                                </div>
                                            </div>
                                        </form>

                                        
                                    </div>
                                </div>


                                {{-- modal signature --}}
                                <div class="modal fade" id="modalSignature" tabindex="-1" aria-labelledby="modalSignatureLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="modalSignatureLabel">Sign Here</h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="sign-container d-none">
                                                    <div class="wrapper-signature">
                                                        <canvas id="signature-pad" class="signature-pad" width=400 height=200></canvas>
                                                    </div>
                                                      
                                                    {{-- <button id="save-png">Save as PNG</button>
                                                    <button id="save-jpeg">Save as JPEG</button>
                                                    <button id="save-svg">Save as SVG</button> --}}
                                                    <button id="undo" class="btn btn-sm btn-default">Undo</button>
                                                    <button id="clear" class="btn btn-sm btn-default">Clear</button>
                                                    <div class="mt-2 save-container">
                                                        <button class="btn btn-sm w-100 btn-primary" type="button" onclick="submitData()">@lang('view.save')</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

							</div>
							<!--end::Container-->
						</div>
						<!--end::Post-->
					</div>
					<!--end::Content-->

                    @include('partials.footer')
				</div>
				<!--end::Wrapper-->
			</div>
			<!--end::Page-->
		</div>
		<!--end::Root-->
		<!--begin::Scrolltop-->
		<div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
			<!--begin::Svg Icon | path: icons/duotune/arrows/arr066.svg-->
			<span class="svg-icon">
				<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
					<rect opacity="0.5" x="13" y="6" width="13" height="2" rx="1" transform="rotate(90 13 6)" fill="black" />
					<path d="M12.5657 8.56569L16.75 12.75C17.1642 13.1642 17.8358 13.1642 18.25 12.75C18.6642 12.3358 18.6642 11.6642 18.25 11.25L12.7071 5.70711C12.3166 5.31658 11.6834 5.31658 11.2929 5.70711L5.75 11.25C5.33579 11.6642 5.33579 12.3358 5.75 12.75C6.16421 13.1642 6.83579 13.1642 7.25 12.75L11.4343 8.56569C11.7467 8.25327 12.2533 8.25327 12.5657 8.56569Z" fill="black" />
				</svg>
			</span>
			<!--end::Svg Icon-->
		</div>
		<!--end::Scrolltop-->
	<!--end::Main-->
    <!--begin::Global Javascript Bundle(used by all pages)-->
    <script src="{{ asset('js/plugins.bundle.js') }}"></script>
    <script src="{{ asset('js/scripts.bundle.js') }}"></script>
    <!--end::Global Javascript Bundle-->
    <!--begin::Page Vendors Javascript(used by this page)-->
    <script src="{{ asset('js/datatables.min.js') }}"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    {{-- localization --}}
	<script src="/js/lang.js"></script>
    {{-- <script src="{{ asset('js/select2.min.js') }}"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
    <script src="{{ mix('dist/js/guestbook.js') }}"></script>

</body>
</html>
