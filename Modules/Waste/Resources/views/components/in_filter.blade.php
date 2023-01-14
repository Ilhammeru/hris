@push('styles')
    <style>
        .in-filter {
            border-radius: 12px;
            box-shadow: rgb(49 53 59 / 12%) 0px 1px 6px 0px;
            width: unset;
            padding: 10px 18px;
        }
        .filter-item {
            margin-bottom: 10px;
        }
        .filter-item .title {
            margin-bottom: 5px;
            font-weight: bolder;
        }
    </style>
@endpush

<form method="POST" id="form-filter-in">
    <div class="in-filter">
        <div class="filter-item">
            <p class="title">Date In</p>
            <div class="form-group">
                <input type="text" placeholder="Date in" id="date_in_range" class="form-control form-control-sm">
                <input type="hidden" id="date_start" name="date_start" value="{{ date('Y-m-d') }}">
                <input type="hidden" id="date_end" name="date_end" value="{{ date('Y-m-d') }}">
            </div>
        </div>
        <div class="filter-item">
            <p class="title">Exp Date</p>
            <div class="form-group">
                <input type="text" placeholder="Exp Date" id="date_exp_filter" name="date_exp" class="form-control form-control-sm">
                <input type="hidden" id="exp_start" name='exp_start'>
                <input type="hidden" id="exp_end" name='exp_end'>
            </div>
        </div>
        <div class="filter-item">
            <p class="title">Code</p>
            <div class="form-check mb-3">
                <input class="form-check-input check-code" onchange="selectAllCode(this)" type="checkbox" value="0" name="codes[]" id="code_all">
                <label class="form-check-label" for="code_all">
                    All
                </label>
            </div>
            @foreach ($codes as $code)
                <div class="form-check mb-3">
                    <input class="form-check-input check-code individual-code" onchange="updateSelectAll(this)" type="checkbox" value="{{ $code->id }}" name="codes[]" id="code_{{ $code->id }}">
                    <label class="form-check-label" for="code_{{ $code->id }}">
                        {{ $code->code }}
                    </label>
                </div>
            @endforeach
        </div>
        <div class="text-end">
            <button class="btn btn-primary btn-sm" type="button" id="apply-filter" onclick="filterIn()">Apply</button>
        </div>
    </div>
</form>