<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $pageTitle }}</title>

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
		.notif-message-badge {
            top: 5px;
            left: 80px;
			font-size: 8px;
        }
	</style>

	@stack('styles')

	<!-- Main Quill library -->
	<script src="//cdn.quilljs.com/1.3.6/quill.js"></script>
	<script src="//cdn.quilljs.com/1.3.6/quill.min.js"></script>

	{{-- pusher --}}
	<script src="https://js.pusher.com/7.2/pusher.min.js"></script>

    {{-- <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet"> --}}
</head>
<body id="kt_body" class="header-tablet-and-mobile-fixed aside-enabled">
    <!--begin::Main-->
		<!--begin::Root-->
		<div class="d-flex flex-column flex-root">
			<!--begin::Page-->
			<div class="page d-flex flex-row flex-column-fluid">
				@include('partials.sidebar')
				<!--begin::Wrapper-->
				<div class="wrapper d-flex flex-column flex-row-fluid" id="kt_wrapper">
					@include('partials.header', [
                        'pageTitle' => $pageTitle,
                        'has_action_header' => $has_action_header,
                        'btn_type' => $btn_type,
                        'text' => $text,
                        'onclick' => $onclick,
                        'onclick_href' => $onclick_href
                    ])

					<!--begin::Content-->
					<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
						<!--begin::Post-->
						<div class="post d-flex flex-column-fluid" id="kt_post">
							<!--begin::Container-->
							<div id="kt_content_container" class="container-xxl">

								@yield('content')

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

	@include('partials.notify')

	{{-- tippy.js --}}
	<script src="https://unpkg.com/@popperjs/core@2/dist/umd/popper.min.js"></script>
	<script src="https://unpkg.com/tippy.js@6/dist/tippy-bundle.umd.js"></script>

	{{-- date range picker --}}
	<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
	<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <!--begin::Global Javascript Bundle(used by all pages)-->
    <script src="{{ asset('js/plugins.bundle.js') }}"></script>
    <script src="{{ asset('js/scripts.bundle.js') }}"></script>
    <!--end::Global Javascript Bundle-->
	<!--begin::Page Vendors Javascript(used by this page)-->
	<script src="{{ asset('js/datatables.min.js') }}"></script>
	{{-- <script src="{{ asset('js/select2.min.js') }}"></script> --}}

	{{-- sweetalert --}}
	<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

	{{-- master javascript --}}
	<script src="{{ mix('dist/js/master.js') }}"></script>

	{{-- localization --}}
	<script src="/js/lang.js"></script>

	<!--end::Page Vendors Javascript-->
	<script>
		const base_url = window.location.origin;
		var dtLanguage = {
			"sEmptyTable": "Tidak ada data yang tersedia pada tabel ini",
			"sProcessing": "Sedang memproses...",
			"sLengthMenu": "Tampilkan _MENU_ entri",
			"sZeroRecords": "Tidak ditemukan data yang sesuai",
			"sInfo": "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
			"sInfoEmpty": "Menampilkan 0 sampai 0 dari 0 entri",
			"sInfoFiltered": "(disaring dari _MAX_ entri keseluruhan)",
			"sInfoPostFix": "",
			"sSearch": "",
			"sUrl": "",
			"oPaginate": {
				"sFirst": "<<",
				"sPrevious": "<",
				"sNext": ">",
				"sLast": ">>"
			}
		};

		$.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

		// init all necessary data
		initGeneral();

		// pusher init
		Pusher.logToConsole = true;
		var pusher = new Pusher('3581279f83a8efceaa6a', {
            cluster: 'ap1',
            encrypted: true
        });

		// pusher for message notification
		let targetNotif = $('.notif-message-badge');
		var channelMessage = pusher.subscribe('message-from-vacancy');
		channelMessage.bind('Modules\\Recruitment\\Events\\MessageFromVacancy', function(data) {
			// TODO: Do something with notification
			targetNotif.html(data.count);
			if (data.count > 0) {
				targetNotif.removeClass('d-none');
			}
		});

		function initGeneral() {

		}

		function setLoading(id, start) {
			let elem = $('#' + id);
			let loading = `<div class="spinner-border" style="width: 1em; height: 1em" role="status">
					<span class="visually-hidden"></span>
					</div>`;
			if (start) {
				elem.html(loading);
				elem.prop('disabled', true);
			} else {
				elem.html(`{{ __('setting::messages.save') }}`);
				elem.prop('disabled', false);
			}
		}

		function setNotif(error, message) {
			if (error) {
				if (typeof message == 'object' || typeof message == 'array') {
					for (let a = 0; a < message.length; a++) {
						iziToast.error({
							message: message[a],
							position: "topRight"
						});
					}
				} else {
					iziToast.error({
						message: message,
						position: "topRight"
					});
				}
			} else {
				iziToast.success({
					message: message,
					position: "topRight"
				});
			}
		}

		function resetForm(id) {
			document.getElementById(id).reset();
		}

		function setDataTable(tableId, columns, route) {
			let dt = $('#' + tableId).DataTable({
				processing: true,
				serverSide: true,
				responsive: true,
				scrollX: true,
				ajax: route,
				columns: columns,
				drawCallback: function (settings, json) {
					tippy('[data-tippy-content]');
				},
				order: [[0, 'desc']]
			});
			return dt;
		}

		function deleteMaster(title, cancelText, confirmText, url, dt) {
			Swal.fire({
                title: title,
                showCancelButton: true,
                cancelButtonText: cancelText,
                confirmButtonText: confirmText,
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'delete',
                        url: url,
                        success: function(res) {
                            setNotif(false, res.message);
                            dt.ajax.reload();
                        },
                        error: function(err) {
                            setNotif(true, err.responseJSON == undefined ? err.responseText : err.responseJSON);
                        }
                    })
                }
            })
		}
	</script>
	@stack('scripts')

    {{-- <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script> --}}

</body>
</html>
