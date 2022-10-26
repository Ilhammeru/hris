<div class="mb-3 row">
    <label for="email" class="col-form-label col-md-2">{{ __('user::users.email') }}:</label>
    <div class="col-md-6">
        <input type="text" class="form-control form-control-sm" id="email" name="email" placeholder="{{ __('user::users.email_placeholder') }}" value="{{ $data ? $data->email : '' }}">
    </div>
</div>
<div class="mb-3 row">
    <label for="password" class="col-form-label col-md-2">{{ __('user::users.password') }}:</label>
    <div class="col-md-6">
        <input type="password" class="form-control form-control-sm" id="password" name="password" placeholder="{{ __('user::users.password') }}">
        @if ($user)
            <span style="font-size: 12px;" class="text-muted">Leave it blank if you don't want to change the password</span>
        @endif
    </div>
</div>
<div class="mb-3 row">
    <label for="retype_password" class="col-form-label col-md-2">{{ __('user::users.retype_password') }}:</label>
    <div class="col-md-6">
        <input type="password" class="form-control form-control-sm" id="retype_password" name="retype_password" placeholder="{{ __('user::users.password') }}">
    </div>
</div>
<div class="mb-3 row">
    <label for="role" class="col-form-label col-md-2">{{ __('user::users.role') }}:</label>
    <div class="col-md-6">
        <select name="role" id="role" class="form-control form-control-sm">
            @foreach ($roles as $role)
                @if ($user)
                    <option value="{{ $role->id }}" {{ $user->role == $role->id ? 'selected' : '' }}>{{ ucfirst($role->name) }}</option>
                @else
                    <option value="{{ $role->id }}">{{ ucfirst($role->name) }}</option>
                @endif
            @endforeach
        </select>
    </div>
</div>