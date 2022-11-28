<div class="mt-10">
    <div class="form-group row mb-3">
        <div class="col-md-6">
            <label for="" class="col-form-label">{{ __('recruitment::view.select_setting_to_show_notif_option') }}</label>
            <select name="select-recruitment-step" id="select-recruitment-step" class="form-control form-control-sm" onchange="showNotificationSetup()"></select>
        </div>
    </div>
</div>

<div class="divider border-bottom mt-5 mb-5"></div>

<div class="container-notification-setup row">
    <div class="col-md-6">
        <div class="form-group row mb-3">
            <label for="notif_type" class="col-form-label col-md-3">{{ __('recruitment::view.notif_type') }} :</label>
            <div class="col-md-9">
                <input type="text" class="form-control form-control-sm" id="notif_type">
            </div>
        </div>
        <div class="form-group row mb-3">
            <label for="notif_type" class="col-form-label col-md-3">{{ __('recruitment::view.notif_message') }} :</label>
            <div class="col-md-9">
                <div id="quill-notif-message"></div>
            </div>
        </div>
    </div>
</div>