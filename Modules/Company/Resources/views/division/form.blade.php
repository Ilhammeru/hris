@php
    if (isset($data)) {
        $dept = $data;
        $selected_dept = $dept->department_id;

    } else {
        $dept = null;
        $selected_dept = null;
    }
@endphp

<div class="mb-3 row">
    <label for="name" class="col-form-label col-md-3">{{ __('company::company.form_division_name') }}</label>
    <div class="col-md-6">
        <input type="text" id="department-name" value="{{ $dept ? $dept->name : '' }}" name="name" placeholder="{{ __('company::company.form_division_name_ps') }}" class="form-control form-control-sm">
    </div>
</div>
<div class="mb-3 row">
    <label for="department_div" class="col-form-label col-md-3">{{ __('company::company.department') }}</label>
    <div class="col-md-6">
        <select name="department_id" class="form-control form-control-sm" id="department_div">
            @foreach ($departments as $department)
                <option value="{{ $department->id }}" {{ $selected_dept == $department->id ? 'selected' : '' }}>{{ $department->name }}</option>
            @endforeach
        </select>
    </div>
</div>