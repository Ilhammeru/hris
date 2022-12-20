<div class="form-group row mb-3">
    <label for="title" class="col-form-label col-md-3 required">{{ __('recruitment::view.title') }}:</label>
    <div class="col-md-6">
        <input type="text" class="form-control form-control-sm" value="{{ $data ? $data->title : '' }}" name="title" id="title" placeholder="{{ __('recruitment::view.title') }}">
    </div>
</div>
<div class="form-group row mb-3">
    <label for="department" class="col-form-label col-md-3 required">{{ __('recruitment::view.department') }}:</label>
    <div class="col-md-6">
        <select name="department" class="form-control form-control-sm" id="department">
            @foreach ($departments as $department)
                <option value="{{ $department->id }}" @if($data) {{ $data->department_id == $department->id ? 'selected' : '' }} @endif>{{ ucfirst($department->name) }}</option>
            @endforeach
        </select>
    </div>
</div>
<div class="form-group row mb-3">
    <label for="division" class="col-form-label col-md-3 required">{{ __('recruitment::view.division') }}:</label>
    <div class="col-md-6">
        <select name="division" class="form-control form-control-sm" id="division">
            @foreach ($divisions as $division)
                <option value="{{ $division->id }}" @if($data) {{ $data->division_id == $division->id ? 'selected' : '' }} @endif>{{ ucfirst($division->name) }}</option>
            @endforeach
        </select>
    </div>
</div>
<div class="form-group row mb-3">
    <label for="job_type" class="col-form-label col-md-3 required">{{ __('recruitment::view.job_type') }}:</label>
    <div class="col-md-6">
        <select name="job_type" class="form-control form-control-sm" id="job_type">
            @foreach ($job_type as $job)
                <option value="{{ $job['id'] }}" @if($type_form != 'create') {{ $data->job_type_id == $job['id'] ? 'selected' : '' }} @endif>{{ ucfirst($job['name']) }}</option>
            @endforeach
        </select>
    </div>
</div>
<div class="form-group row mb-3">
    <label for="working_type" class="col-form-label col-md-3 required">{{ __('recruitment::view.working_type') }}:</label>
    <div class="col-md-6">
        <select name="working_type" class="form-control form-control-sm" id="working_type">
            @foreach ($working_type as $work)
                <option value="{{ $work['id'] }}" @if($type_form != 'create') {{ $data->working_type == $work['id'] ? 'selected' : '' }} @endif>{{ ucfirst($work['name']) }}</option>
            @endforeach
        </select>
    </div>
</div>
<div class="form-group row mb-3">
    <label for="needs" class="col-form-label col-md-3 required">{{ __('recruitment::view.needs') }}</label>
    <div class="col-md-6">
        <input type="number" name="needs" value="{{ $data ? $data->needs : '' }}" class="form-control form-control-sm" id="needs" placeholder="{{ __('recruitment::view.placeholder_needs') }}">
    </div>
</div>
<div class="form-group row mb-3">
    <label for="start_date" class="col-form-label col-md-3 required">{{ __('recruitment::view.date_range') }}</label>
    <div class="col-md-6">
        <input type="text" name="dates" id="dates" value="{{ $data ? date('Y/m/d', strtotime($data->start)) . ' - ' . date('Y/m/d', strtotime($data->end)) : '' }}" class="form-control form-control-sm">
    </div>
</div>
{{-- <div class="form-group row mb-3">
    <label for="start_date" class="col-form-label col-md-3">{{ __('recruitment::view.pic_recruitment') }}</label>
    <div class="col-md-6">
        <input type="text" name="dates" id="dates" class="form-control form-control-sm">
    </div>
</div> --}}
<div class="form-group row mb-3">
    <label for="publish" class="col-form-label col-md-3">{{ __('recruitment::view.publish') }}</label>
    <div class="col-md-6" style="align-self: center;">
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" @if($data) {{ $data->is_active ? 'checked' : '' }} @endif name="publish" role="switch" id="publish">
            <label class="form-check-label" for="publish"></label>
        </div>
    </div>
</div>
<div class="form-group row mb-3">
    <label for="tag" class="col-form-label col-md-3">{{ __('recruitment::view.tag') }}</label>
    <div class="col-md-6" style="align-self: center !important;">
        <div class="tags">
            <div class="tag d-flex align-items-center g-2" onclick="createTag()">
                <i class="bi bi-plus-circle-dotted" style="line-height: 0 !important; margin-right: 5px !important;"></i>
                <span>Create</span>
            </div>
        </div>
        <input type="hidden" name="tag" id="tag">
    </div>
</div>
<div class="form-group row">
    <label for="description" class="col-form-label col-md-3 required">{{ __('recruitment::view.description') }}:</label>
    <div class="col-md-6">
        <div id="description" style="height: 150px;"></div>
    </div>
</div>


<!-- Modal TAG -->
<div class="modal fade" id="modalTag" tabindex="-1" aria-labelledby="modalTagLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('employee.recruitment.add.tag') }}" method="POST" id="form_tag">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalTagLabel">Add Tag</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pt-0">
                    <div class="form-group">
                        <label for="tag_input" class="col-form-label">{{ __('recruitment::view.tag') }}</label>
                        <input type="text" class="form-control form-control-sm" placeholder="Design" name="tag" id="tag_input">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="btn-save-tag" onclick="saveTag()" class="btn btn-primary">{{ __('view.save') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function createTag() {
        $('#modalTag').modal('show');
    }

    function saveTag() {
        $.ajax({
            type: 'POST',
            url: "{{ route('employee.recruitment.add.tag') }}",
            data: {
                tag: $('#tag_input').val()
            },
            beforeSend: function() {
                setLoading('btn-save-tag', true);
            },
            success: function(res) {
                setLoading('btn-save-tag', false);
                setNotif(false, res.message);
                $('#modalTag').modal('hide');
                
                // reload tag
                getTag();
            },
            error: function(err) {
                setLoading('btn-save-tag', false);
                setNotif(true, err.responseJSON);
            }
        })
    }
</script>