<div class="mb-3 row">
    <label for="name" class="col-form-label col-md-3">{{ __('user::permissions.form_permission_name') }}</label>
    <div class="col-md-6">
        <input type="text" value="{{ $data ? $data->name : '' }}" class="form-control form-control-sm" id="name" name="name" placeholder="{{ __('user::permissions.name_placeholder') }}">
    </div>
</div>
<div class="mb-3 row">
    <label for="permission_group" class="col-form-label col-md-3">{{ __('user::permissions.form_permission_group_name') }}</label>
    <div class="col-md-6">
        <select name="permission_group" class="form-control form-control-sm" id="permission_group"></select>
    </div>
</div>