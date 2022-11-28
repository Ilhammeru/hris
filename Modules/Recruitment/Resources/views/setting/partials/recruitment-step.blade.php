<div class="text-end mt-10">
    <button class="btn btn-sm btn-primary" type="button">{{ __('recruitment::view.create_recruitment_setting') }}</button>
</div>

<div class="divider border-bottom mt-5 mb-5"></div>

<div class="table responsive">
    <table class="table {{ dt_table_class() }}" id="table-recruitment-setting">
        <thead class="{{ dt_head_class() }}">
            <tr>
                <th>#</th>
                <th>name</th>
                <th>step</th>
                <th></th>
            </tr>
        </thead>
    </table>
</div>