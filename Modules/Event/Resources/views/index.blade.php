@extends('event::layouts.master')

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="text-end">
                <a onclick="showFormUpdate()" class="btn btn-primary"><i class="fa fa-plus"></i> {{ __('event::view.add_event') }}</a>
            </div>

            {{-- divider --}}
            <div class="border-top mt-5"></div>

            <div class="table-responsive mt-5">
                <table class="table {{ dt_table_class() }}" id="table-event-list">
                    <thead class="{{ dt_head_class() }}">
                        <tr>
                            <th>#</th>
                            <th>{{ __("event::view.name") }}</th>
                            <th>{{ __('event::view.guestbook') }}</th>
                            <th>{{ __('event::view.option_finisher') }}</th>
                            <th>{{ __('event::view.start_date') }}</th>
                            <th>{{ __('event::view.end_date') }}</th>
                            <th>{{ __('view.action') }}</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    {{-- modal create event --}}
    <div class="modal fade" id="modalEvent" tabindex="-1" aria-labelledby="modalEventLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalEventLabel">@lang('event::view.add_event')</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" id="form-update-event" novalidate>
                        <div class="form-group mb-3">
                            <label for="name" class="text-capitalize pt-2">@lang('event::view.event_name')</label>
                            <input type="text" class="form-control" data-name="name" id="name" name="name">
                            <input type="hidden" name="event_id_field" id="event_id_field">
                            <div class="invalid-feedback" id="name_err">
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="option_finisher" class="text-capitalize pt-2">@lang('event::view.option_finisher')</label>
                            <div class="mt-2">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" checked type="radio" name="option_finisher" id="signature-option" value="1">
                                    <label class="form-check-label" for="signature-option">@lang('event::view.digital_signature')</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="option_finisher" id="confirmation-box-option" value="2">
                                    <label class="form-check-label" for="confirmation-box-option">@lang('event::view.confirmation_box')</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="start_date" class="text-capitalize pt-2">@lang('event::view.event_date')</label>
                            <input type="text" class="form-control datepicker" id="start_date" name="event_date">
                            <div class="invalid-feedback" id="position_id_form_err">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                    <button type="button" onclick="saveEvent()" class="btn btn-primary btn-sm btn-save">@lang('view.submit')</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ mix('dist/js/event.js') }}"></script>
@endpush
