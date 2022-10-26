@php
    $dept = $data ?? null;
@endphp

<div class="mb-3 row">
    <label for="name" class="col-form-label col-md-3">{{ __('company::company.form_department_name') }}</label>
    <div class="col-md-6">
        <input type="text" id="department-name" value="{{ $dept ? $dept->name : '' }}" name="name" placeholder="{{ __('company::company.form_department_name_ps') }}" class="form-control form-control-sm">
    </div>
</div>