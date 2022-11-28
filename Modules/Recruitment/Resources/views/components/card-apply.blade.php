<style>
    .card-apply {
        background: #f4f6fe;
        padding: 5px 12px;
        border-radius: 10px;
    }

    .card-apply > .card-body {
        padding: 5px 12px !important;
    }

    .btn-apply {
        background: #3a36c6;
        color: #fff;
        font-size: 13px;
        width: 100%;
        margin-top: 10px;
    }

    .btn-apply:hover {
        color: #fff;
    }

    .btn-subscribe {
        background: #e2e5fe;
        color: #000;
        font-weight: bold;
        font-size: 13px;
        width: 100%;
        margin-top: 5px;
    }
</style>

<div class="card card-apply">
    <div class="card-body">
        <div class="text-center">
            <p class="m-0">{{ __('recruitment::view.do_you_match') }}</p>

            <a class="btn btn-sm btn-apply" href="{{ route('employee.recruitment.apply-form', $data->id) }}">{{ __('recruitment::view.apply_now') }}</a>
            <button class="btn btn-sm btn-subscribe">{{ __('recruitment::view.subscribe_now') }}</button>
        </div>
    </div>
</div>