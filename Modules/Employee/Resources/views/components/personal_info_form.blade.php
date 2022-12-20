<div class="form-group row mb-3 mt-3">
    <label for="name" class="col-form-label text-capitalize pt-2 col-md-3">{{__("employee::view.name")}}</label>
    <div class="col-md-1">
        <label for="" class="col-form-label text-capitalize pt-2">:</label>
    </div>
    <div class="col-md-8">
        <input type="text" name="name" class="form-control form-control-sm" id="name" placeholder="John Wick" value="{{!empty($data) ? $data->name : ''}}">
    </div>
</div>
<div class="form-group row mb-3">
    <label for="nik" class="col-form-label text-capitalize pt-2 col-md-3">{{__("employee::view.nik")}}</label>
    <div class="col-md-1">
        <label for="" class="col-form-label text-capitalize pt-2">:</label>
    </div>
    <div class="col-md-8">
        <input type="number" name="nik" class="form-control form-control-sm" id="nik" placeholder="357357444777588" value="{{!empty($data) ? $data->nik : ''}}">
    </div>
</div>
<div class="form-group row mb-3">
    <label for="date_of_birth" class="col-form-label text-capitalize pt-2 col-md-3">{{__("employee::view.date_of_birth")}}</label>
    <div class="col-md-1">
        <label for="" class="col-form-label text-capitalize pt-2">:</label>
    </div>
    <div class="col-md-8">
        <input type="date" name="date_of_birth" class="form-control form-control-sm" id="date_of_birth" value="{{!empty($data) ? $data->date_of_birth : ''}}">
    </div>
</div>
<div class="form-group row mb-3">
    <label for="email" class="col-form-label text-capitalize pt-2 col-md-3">{{__("employee::view.email")}}</label>
    <div class="col-md-1">
        <label for="" class="col-form-label text-capitalize pt-2">:</label>
    </div>
    <div class="col-md-8">
        <input type="email" name="email" placeholder="john@gmail.com" class="form-control form-control-sm" id="email" value="{{!empty($data) ? $data->email : ''}}">
    </div>
</div>
<div class="form-group row mb-3">
    <label for="phone" class="col-form-label text-capitalize pt-2 col-md-3">{{__("employee::view.phone")}}</label>
    <div class="col-md-1">
        <label for="" class="col-form-label text-capitalize pt-2">:</label>
    </div>
    <div class="col-md-8">
        <input type="number" name="phone" placeholder="08188875665" class="form-control form-control-sm" id="phone" value="{{!empty($data) ? $data->phone : ''}}">
    </div>
</div>
<div class="form-group row mb-3">
    <label for="address" class="col-form-label text-capitalize pt-2 col-md-3">{{__("employee::view.address")}}</label>
    <div class="col-md-1">
        <label for="" class="col-form-label text-capitalize pt-2">:</label>
    </div>
    <div class="col-md-8">
        <input type="text" name="address" placeholder="Jl. Simpati No 88" class="form-control form-control-sm" id="address" value="{{!empty($data) ? $data->address : ''}}">
    </div>
</div>
<div class="form-group row mb-3">
    <label for="province" class="col-form-label text-capitalize pt-2 col-md-3">{{__("employee::view.province")}}</label>
    <div class="col-md-1">
        <label for="" class="col-form-label text-capitalize pt-2">:</label>
    </div>
    <div class="col-md-8">
        <select name="province_id" id="province" onchange="getAddressAdditional(this, 'city')" class="form-control form-control-sm">
            <option value="" selected disabled>-- {{__('view.choose')}} --</option>
            @foreach($provinces as $province)
                <option value="{{$province->id}}">{{$province->name}}</option>
            @endforeach
        </select>
    </div>
</div>
<div class="form-group row mb-3">
    <label for="city" class="col-form-label text-capitalize pt-2 col-md-3">{{__("employee::view.city")}}</label>
    <div class="col-md-1">
        <label for="" class="col-form-label text-capitalize pt-2">:</label>
    </div>
    <div class="col-md-8">
        <select name="city_id" id="city" disabled onchange="getAddressAdditional(this, 'district')" class="form-control form-control-sm">
        </select>
    </div>
</div>
<div class="form-group row mb-3">
    <label for="district" class="col-form-label text-capitalize pt-2 col-md-3">{{__("employee::view.district")}}</label>
    <div class="col-md-1">
        <label for="" class="col-form-label text-capitalize pt-2">:</label>
    </div>
    <div class="col-md-8">
        <select name="district_id" disabled id="district" onchange="getAddressAdditional(this, 'village')" class="form-control form-control-sm">
        </select>
    </div>
</div>
<div class="form-group row mb-3">
    <label for="village" class="col-form-label text-capitalize pt-2 col-md-3">{{__("employee::view.village")}}</label>
    <div class="col-md-1">
        <label for="" class="col-form-label text-capitalize pt-2">:</label>
    </div>
    <div class="col-md-8">
        <select name="village_id" disabled id="village" class="form-control form-control-sm">
        </select>
    </div>
</div>
