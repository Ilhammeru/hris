<div class="form-group row mb-3 mt-3">
    <label for="department" class="col-form-label text-capitalize pt-2 col-md-3">{{__("employee::view.department")}}</label>
    <div class="col-md-1">
        <label for="" class="col-form-label text-capitalize pt-2">:</label>
    </div>
    <div class="col-md-8">
        <select name="department_id" id="department" onchange="getDivision(this)" class="form-control form-control-sm">
            <option value="" disabled selected>--{{__('view.choose')}} --</option>
            @foreach($departments as $department)
                <option value="{{$department->id}}">{{$department->name}}</option>
            @endforeach
        </select>
    </div>
</div>
<div class="form-group row mb-3 mt-3">
    <label for="division" class="col-form-label text-capitalize pt-2 col-md-3">{{__("employee::view.division")}}</label>
    <div class="col-md-1">
        <label for="" class="col-form-label text-capitalize pt-2">:</label>
    </div>
    <div class="col-md-8">
        <select name="division" disabled id="division" class="form-control form-control-sm"></select>
    </div>
</div>
<div class="form-group row mb-3 mt-3">
    <label for="account_number" class="col-form-label text-capitalize pt-2 col-md-3">{{__("employee::view.account_number")}}</label>
    <div class="col-md-1">
        <label for="" class="col-form-label text-capitalize pt-2">:</label>
    </div>
    <div class="col-md-8">
        <input type="number" name="account_number" class="form-control form-control-sm" id="account_number">
    </div>
</div>
<div class="form-group row mb-3 mt-3">
    <label for="bpjs_ketenagakerjaan" class="col-form-label text-capitalize pt-2 col-md-3">{{__("employee::view.bpjs_ketenagakerjaan")}}</label>
    <div class="col-md-1">
        <label for="" class="col-form-label text-capitalize pt-2">:</label>
    </div>
    <div class="col-md-8">
        <input type="number" name="bpjs_ketenagakerjaan" class="form-control form-control-sm" id="bpjs_ketenagakerjaan">
    </div>
</div>
<div class="form-group row mb-3 mt-3">
    <label for="bpjs_kesehatan" class="col-form-label text-capitalize pt-2 col-md-3">{{__("employee::view.bpjs_kesehatan")}}</label>
    <div class="col-md-1">
        <label for="" class="col-form-label text-capitalize pt-2">:</label>
    </div>
    <div class="col-md-8">
        <input type="number" name="bpjs_kesehatan" class="form-control form-control-sm" id="bpjs_kesehatan">
    </div>
</div>
<div class="form-group row mb-3 mt-3">
    <label for="npwp" class="col-form-label text-capitalize pt-2 col-md-3">{{__("employee::view.npwp")}}</label>
    <div class="col-md-1">
        <label for="" class="col-form-label text-capitalize pt-2">:</label>
    </div>
    <div class="col-md-8">
        <input type="number" name="npwp" class="form-control form-control-sm" id="npwp">
    </div>
</div>
<div class="form-group row mb-3 mt-3">
    <label for="employement_status" class="col-form-label text-capitalize pt-2 col-md-3">{{__("employee::view.employement_status")}}</label>
    <div class="col-md-1">
        <label for="" class="col-form-label text-capitalize pt-2">:</label>
    </div>
    <div class="col-md-8">
        <div class="form-check mb-3">
            <input class="form-check-input" type="radio" name="employement_status" onchange="changeEmployementStatus(this)" value="1" id="employement_status">
            <label class="form-check-label" for="employement_status">
                {{__('employee::view.permanent')}}
            </label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="employement_status" onchange="changeEmployementStatus(this)" value="2" id="internship" checked>
            <label class="form-check-label" for="internship">
                {{__('employee::view.internship')}}
            </label>
        </div>
    </div>
</div>
<div class="form-group row mb-3 mt-3">
    <label for="employement_status" class="col-form-label text-capitalize pt-2 col-md-3">{{__("employee::view.employement_status")}}</label>
    <div class="col-md-1">
        <label for="" class="col-form-label text-capitalize pt-2">:</label>
    </div>
    <div class="col-md-8">
        <input id="employement_status_file" name="file-data" type="file" class="form-control form-control-sm">
    </div>
</div>
<div class="form-group row mb-3 mt-3 permanent-wrapper d-none">
    <label for="permanent_date" class="col-form-label text-capitalize pt-2 col-md-3">{{__("employee::view.permanent_date")}}</label>
    <div class="col-md-1">
        <label for="" class="col-form-label text-capitalize pt-2">:</label>
    </div>
    <div class="col-md-8">
        <input id="permanent_date" name="permanent_date" type="date" class="form-control form-control-sm">
    </div>
</div>
<div class="form-group row mb-3 mt-3 internship-wrapper">
    <label for="internship_date" class="col-form-label text-capitalize pt-2 col-md-3">{{__("employee::view.internship_date")}}</label>
    <div class="col-md-1">
        <label for="" class="col-form-label text-capitalize pt-2">:</label>
    </div>
    <div class="col-md-8">
        <input id="internship_date" name="internship_date" type="date" class="form-control form-control-sm">
    </div>
</div>

