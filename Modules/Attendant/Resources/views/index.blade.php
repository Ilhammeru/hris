@extends('attendant::layouts.master')

@push('styles')
    {{-- <link rel="stylesheet" href={{ asset('plugins/filepond/dist/filepond.css') }}"> --}}
    <link href="https://unpkg.com/filepond@^4/dist/filepond.css" rel="stylesheet" />
    <link rel="stylesheet" href={{ asset('plugins/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css') }}">
@endpush

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="text-end">
                <a onclick="showFormUpdate()" class="btn btn-primary"><i class="fa fa-plus"></i> {{ __('attendant::view.add_attendant') }}</a>
                <a onclick="showFormImport()" class="btn btn-info"><i class="fa fa-file-import"></i> {{ __('attendant::view.import_attendant') }}</a>
            </div>

            {{-- divider --}}
            <div class="border-top mt-5"></div>

            <div class="table-responsive mt-5">
                <table class="table {{ dt_table_class() }}" id="table-attendant-list">
                    <thead class="{{ dt_head_class() }}">
                        <tr>
                            <th>#</th>
                            <th>{{ __("attendant::view.name") }}</th>
                            <th>{{ __('attendant::view.employee_id') }}</th>
                            <th>{{ __('attendant::view.position') }}</th>
                            <th>{{ __('attendant::view.action') }}</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    {{-- modal create attendant --}}
    <div class="modal fade" id="modalCreateAttendant" tabindex="-1" aria-labelledby="modalCreateAttendantLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalCreateAttendantLabel">@lang('attendant::view.upload_file')</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" id="form-update" novalidate>
                        <div class="form-group mb-3">
                            <label for="name" class="text-capitalize pt-2">@lang('attendant::view.name')</label>
                            <input type="text" class="form-control" data-name="name" id="name" name="name">
                            <div class="invalid-feedback" id="name_err">
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="employee_id" class="text-capitalize pt-2">@lang('attendant::view.employee_id')</label>
                            <input type="text" class="form-control" data-name="employee" id="employee_id" name="employee_id">
                            <div class="invalid-feedback" id="employee_id_err">
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="position_id_form" class="text-capitalize pt-2">@lang('attendant::view.position')</label>
                            <select name="position_id" data-name="position" id="position_id_form" class="form-control">
                                @foreach (Modules\Company\Entities\Position::all() as $item)
                                    <option value="{{ $item->id }}">{{ Str::ucfirst($item->name) }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" id="position_id_form_err">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                    <button type="button" onclick="saveAttendant()" class="btn btn-primary btn-sm btn-save">@lang('view.submit')</button>
                </div>
            </div>
        </div>
    </div>

    {{-- modal import --}}
    <div class="modal fade" id="modalImport" tabindex="-1" aria-labelledby="modalImportLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalImportLabel">@lang('attendant::view.upload_file')</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" id="form-import" enctype="multipart/form-data">
                        <div class="form-group mb-3">
                            {{-- <label for="" class="text-capitalize pt-2">@lang('attendant::view.file')</label> --}}
                            <input type="file"
			                    class="filepond"
			                    name="file"
			                    id="filepond"
			                    multiple
			                    data-allow-reorder="true"
			                    data-max-file-size="3MB"
			                    data-max-files="1">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                    <button type="button" onclick="importFile()" class="btn btn-primary btn-sm btn-save" disabled>@lang('attendant::view.import')</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')

    {{-- filepond --}}
    {{-- <script src="{{ asset('plugins/filepond/dist/filepond.js') }}"></script> --}}
    <script src="https://unpkg.com/filepond@^4/dist/filepond.js"></script>
    <script src="{{ asset('plugins/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.min.js') }}"></script>
    <script src="{{ asset('plugins/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.min.js') }}"></script>
    <script src="{{ asset('plugins/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.js') }}"></script>
    <script src="{{ asset('plugins/filepond-plugin-image-exif-orientation/dist/filepond-plugin-image-exif-orientation.min.js') }}"></script>
    <script src="{{ mix('dist/js/attendant.js') }}"></script>
@endpush
