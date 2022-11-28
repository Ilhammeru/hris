<div class="form-group row mb-3">
    <label for="fullname" class="col-form-label col-md-3 required">{{ __('recruitment::view.fullname') }}</label>
    <div class="col-md-9">
        <input type="text" name="fullname" placeholder="{{ __('recruitment::view.fullname') }}" class="form-control form-control-sm" id="fullname">
    </div>
</div>
<div class="form-group row mb-3">
    <label for="email" class="col-form-label col-md-3 required">{{ __('recruitment::view.email') }}</label>
    <div class="col-md-9">
        <input type="text" name="email" placeholder="{{ __('recruitment::view.email') }}" class="form-control form-control-sm" id="email">
    </div>
</div>
<div class="form-group row mb-3">
    <label for="phone" class="col-form-label col-md-3 required">{{ __('recruitment::view.phone') }}</label>
    <div class="col-md-9">
        <div class="input-group">
            <span class="input-group-text" style="font-size: 12px;" id="basic-addon1">+62</span>
            <input type="number" name="phone" placeholder="{{ __('recruitment::view.phone') }}" class="form-control form-control-sm" id="phone">
        </div>
    </div>
</div>
<div class="form-group row mb-3">
    <label for="address" class="col-form-label col-md-3 required">{{ __('recruitment::view.address') }}</label>
    <div class="col-md-9">
        <input type="text" name="address" placeholder="{{ __('recruitment::view.address') }}" class="form-control form-control-sm" id="address">
    </div>
</div>
<div class="form-group row mb-3">
    <label for="province" class="col-form-label col-md-3 required">{{ __('recruitment::view.province') }}</label>
    <div class="col-md-9">
        <select name="province" id="province" class="form-control form-control-sm" onchange="getCity()">
            <option value="" disabled selected>-- Choose --</option>
            @foreach ($provinces as $province)
                <option value="{{ $province->id }}">{{ $province->name }}</option>
            @endforeach
        </select>
    </div>
</div>
<div class="form-group row mb-3">
    <label for="city" class="col-form-label col-md-3 required">{{ __('recruitment::view.city') }}</label>
    <div class="col-md-9">
        <select name="city" id="city" class="form-control form-control-sm" onchange="getDistrict()">
            <option disabled selected>-- Choose --</option>
        </select>
    </div>
</div>
<div class="form-group row mb-3">
    <label for="district" class="col-form-label col-md-3 required">{{ __('recruitment::view.district') }}</label>
    <div class="col-md-9">
        <select name="district" id="district" class="form-control form-control-sm" onchange="getVillage()">
            <option disabled selected>-- Choose --</option>
        </select>
    </div>
</div>
<div class="form-group row mb-3">
    <label for="village" class="col-form-label col-md-3 required">{{ __('recruitment::view.village') }}</label>
    <div class="col-md-9">
        <select name="village" id="village" class="form-control form-control-sm">
            <option disabled selected>-- Choose --</option>
        </select>
    </div>
</div>