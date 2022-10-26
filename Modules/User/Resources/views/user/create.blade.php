@php
    $user = $data ?? null;
@endphp

@extends('user::layouts.master')

@section('content')
    <div class="card">
        <div class="card-body">
            {{-- begin::action back --}}
            <div class="text-start mb-5">
                <a class="btn btn-secondary btn-sm"
                    href="{{ route('user.list') }}">
                    {{ __('user::users.back') }}
                </a>
            </div>
            {{-- end::action back --}}

            {{-- begin::form --}}
            <form action="{{ $user ? route('user.update', $user ? $user->id : '') : route('user.store') }}" id="form-user" method="{{ $user ? 'PATCH' : 'POST' }}">
                @include('user::user.form', ['data' => $user])

                <button class="btn btn-sm btn-primary" id="btn-save-user" type="button" onclick="save()">{{ __('user::users.save') }}</button>
            </form>
            {{-- end::form --}}
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $('#role').select2();

        function save() {
            let data = $('#form-user').serialize();
            let action = $('#form-user').attr('action');
            let method = $('#form-user').attr('method');

            $.ajax({
                type: method,
                url: action,
                data: data,
                beforeSend: function() {
                    setLoading('btn-save-user', true);
                },
                success: function(res) {
                    console.log(res);
                    setLoading('btn-save-user', false);
                    setNotif(false, res.message);
                    resetForm('form-user');
                },
                error: function(err) {
                    console.log(err);
                    setLoading('btn-save-user', false);
                    setNotif(true, err.responseJSON);
                }
            })
        }
    </script>
@endpush