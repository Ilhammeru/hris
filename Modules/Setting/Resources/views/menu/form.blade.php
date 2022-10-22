<div class="mb-3 row">
    <label for="name" class="col-sm-2 col-form-label">{{ __('setting::messages.menu_form_name') }}</label>
    <div class="col-sm-6">
        <input type="text" name="name"
            placeholder="{{ __('setting::messages.menu_form_name_ph') }}"
            class="form-control form-control-sm"
            value="{{ $data ? $data->name : '' }}">
    </div>
</div>
<div class="mb-3 row">
    <label for="url" class="col-sm-2 col-form-label">{{ __('setting::messages.menu_form_url') }}</label>
    <div class="col-sm-6">
        <input type="text" name="url"
            placeholder="{{ __('setting::messages.menu_form_url_ph') }}"
            class="form-control form-control-sm"
            value="{{ $data ? $data->url : '' }}">
    </div>
</div>
<div class="mb-3 row">
    <label for="parent" class="col-sm-2 col-form-label">{{ __('setting::messages.menu_form_url') }}</label>
    <div class="col-sm-6">
        <select name="parent" id="parent" class="form-control form-control-sm">
            <option value="" selected disabled>-- Choose --</option>
            @foreach ($parent_list as $item)
                @if ($data)
                    <option value="{{ $item->id }}" {{ $data->parent == $item->id ? 'selected' : '' }}>{{ ucfirst($item->name) }}</option>
                @else
                    <option value="{{ $item->id }}">{{ ucfirst($item->name) }}</option>
                @endif
            @endforeach
        </select>
    </div>
</div>