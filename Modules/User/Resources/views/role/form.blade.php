<div class="mb-3 row">
    <label for="name"
        class="col-form-label col-md-2">
        {{ __('user::roles.form_role_name') }}:
    </label>
    <div class="col-md-6">
        <input type="text"
            placeholder="{{ __('user::roles.name_placeholder') }}"
            class="form-control form-control-sm"
            name="name"
            id="name"
            value="{{ $data ? $data->name : '' }}">
    </div>
</div>
<div class="mb-3 row">
    <label for="permission"
        class="col-form-label col-md-2">
        {{ __('user::roles.form_role_permission') }}:
    </label>
    <div class="row">
        @foreach ($permissions as $item)
            <div class="col-md-4 mb-4">
                <p class="mb-5 fw-bolder">{{ ucfirst($item->name) }}</p>

                @foreach ($item->permissions as $permission)
                    <div class="form-check mb-3">
                        <input class="form-check-input"
                            type="checkbox"
                            value="{{ $permission->id }}"
                            {{ $permission->checked ? 'checked' : '' }}
                            name="permissions[]"
                            id="flexCheckDefault-{{ $permission->id }}">
                        <label class="form-check-label"
                            for="flexCheckDefault-{{ $permission->id }}">
                            {{ $permission->name }}
                        </label>
                    </div>
                @endforeach
            </div>
        @endforeach
    </div>
</div>