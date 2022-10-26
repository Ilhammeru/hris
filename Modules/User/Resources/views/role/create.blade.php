@php
    if($is_edit) {
        $attach = [
            'permissions' => $permissions,
            'data' => $data,
            'role_permission' => $role_permission
        ];
    } else {
        $attach = [
            'permissions' => $permissions,
            'data' => null,
            'role_permission' => null
        ];
    }
@endphp

@extends('user::layouts.master')

@section('content')
    {{-- begin::card --}}
    <div class="card">
        <div class="card-body">
            {{-- begin::back-button --}}
            <div class="mb-5 text-start">
                <a href="{{ route('user.role') }}" class="btn btn-secondary btn-sm">{{ __('user::roles.back') }}</a>
            </div>
            {{-- end::back-button --}}

            {{-- begin::form --}}
            <form action="{{ $is_edit ? route('user.role.update', $data ? $data->id : 0) : route('user.role.store') }}"
                id="form-role"
                method="{{ $is_edit ? 'PATCH' : 'POST' }}">

                {{-- begin::field --}}
                @include('user::role.form', $attach)
                {{-- end::field --}}
                
                {{-- begin::form-submit --}}
                <button class="btn btn-sm btn-primary" id="btn-save-role" type="button" onclick="save()">{{__('user::roles.form_save')}}</button>
                {{-- end::form-submit --}}
            </form>
            {{-- end::form --}}
        </div>
    </div>
    {{-- end::card --}}
@endsection

@push('scripts')
    <script>
        'use strict'
        function save() {
            let data = $('#form-role').serialize();
            let url = $('#form-role').attr('action');
            let method = $('#form-role').attr('method');

            $.ajax({
                type: method,
                url: url,
                data: data,
                beforeSend: function() {
                    setLoading('btn-save-role', true);
                },
                success: function() {
                    setNotif(false, 'Role Successfully stored');
                    setLoading('btn-save-role', false);
                    @if(!$is_edit)
                        resetForm('form-role');
                    @endif
                },
                error: function(err) {
                    setNotif(true, err.responseJSON);
                    setLoading('btn-save-role', false);
                }
            })
        }
    </script>
@endpush