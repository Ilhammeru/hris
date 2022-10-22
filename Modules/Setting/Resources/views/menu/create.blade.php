@extends('setting::layouts.master')

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row mb-5">
                <div class="col">
                    <a class="btn btn-secondary btn-sm" href="{{ route('setting.menu') }}">{{ __('setting::messages.back') }}</a>
                </div>
            </div>
            <form class="mt-5" action="{{ $is_edit ? route('setting.menu.update', $data->id) : route('setting.menu.store') }}"
                id="form-create-menu"
                method="{{ $is_edit ? 'PATCH' : 'POST' }}">
                {{-- begin::form --}}
                @include('setting::menu.form', ['data' => $data])
                {{-- end::form --}}

                {{-- begin::action --}}
                <div class="mt-5 row">
                    <div class="col">
                        <button class="btn btn-primary btn-sm"
                            id="btn-save-menu"
                            type="button"
                            onclick="save()">{{ __('setting::messages.save') }}</button>
                    </div>
                </div>
                {{-- end::action --}}
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        'use strict'
        $(document).ready(function() {
            $('#parent').select2();
        });

        function save() {
            let data = $('#form-create-menu').serialize();
            let method = $('#form-create-menu').attr('method');
            let url = $('#form-create-menu').attr('action');

            $.ajax({
                type: method,
                url: url,
                data: data,
                dataType: 'json',
                beforeSend: function() {
                    setLoading('btn-save-menu', true);
                },
                success: function(res) {
                    setLoading('btn-save-menu', false);
                    setNotif(false, `{{ __('setting::messages.success_save') }}`)
                    @if(!$is_edit)
                    reset();
                    @endif
                },
                error: function(err) {
                    setLoading('btn-save-menu', false);
                    setNotif(true, err.responseJSON);
                }
            })
        }

        function reset() {
            document.getElementById('form-create-menu').reset();
        }
    </script>
@endpush