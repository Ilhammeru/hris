@extends('recruitment::layouts.master')

@push('styles')
    <style>
        .tags {
            display: flex;
            align-items: center;
            width: auto;
            gap: 5px;
            flex-wrap: wrap;
        }
        
        .tag {
            padding: 2px 8px;
            border: 1px solid #a3a2da;
            background: #f3f2fa;
            border-radius: 20px;
            font-size: 12px;
            color: #a3a2da;
            cursor: pointer;
        }

        .tag.active {
            background: #3a36c6;
            color: #fff;
        }
    </style>
@endpush

@section('content')

    {{-- begin::card-form --}}
    <div class="card">
        <div class="card-body">
            {{-- begin::navigate-action --}}
            <a href="{{ route('employee.recruitment') }}" class="btn btn-secondary btn-sm mb-5">{{ __('view.back') }}</a>
            {{-- end::navigate-action --}}

            <div class="divider border-bottom w-100 mb-5"></div>

            <form action="{{ route('employee.recruitment.store') }}" method="POST" id="form_vacancy">
                @include('recruitment::form')

                {{-- divider --}}
                <div class="border-top mt-5"></div>

                <div class="text-start mt-5">
                    <button class="btn btn-primary btn-sm mt-5" id="btn-save-vacancy" type="button" onclick="save()">{{ __('view.save') }}</button>
                </div>
            </form>
        </div>
    </div>
    {{-- end::card-form --}}
@endsection

@push('scripts')
    <script>
        $('#dates').daterangepicker({
            locale: {
                format: 'YYYY/MM/DD'
            },
            minDate: getToday()
        });

        getTag();

        var quill = new Quill('#description', {
            placeholder: 'Description',
            theme: 'snow'
        });

        $('#department').select2();
        $('#division').select2();
        $('#job_type').select2();
        $('#working_type').select2();

        let selectedTags = [];

        function getToday() {
            var today = new Date();
            var dd = String(today.getDate()).padStart(2, '0');
            var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
            var yyyy = today.getFullYear();

            today = yyyy + '/' + mm + '/' + dd;
            return today;
        }

        function getTag() {
            let url = "{{ route('employee.recruitment.get-tag', ':id') }}";
            url = url.replace(':id', 0);
            $.ajax({
                type: 'GET',
                url: url,
                beforeSend: function() {
                    
                },
                success: function(res) {
                    data = res.data
                    let div = '';
                    for (let a = 0; a < data.length; a++) {
                        div += `
                            <div class="tag" data-active="0" id="tag-${data[a].id}" onclick="chooseTag(${data[a].id}, '${data[a].name['en']}')">${data[a].name['en']}</div>
                        `;
                    }
                    div += `
                        <div class="tag d-flex align-items-center g-2" onclick="createTag()">
                            <i class="bi bi-plus-circle-dotted" style="line-height: 0 !important; margin-right: 5px !important;"></i>
                            <span>Create</span>
                        </div>
                    `;
                    $('.tags').html(div);
                },
                error: function(err) {
                    setNotif(true, err.responseJSON);
                }
            })
        }

        function chooseTag(id, name)
        {
            let state = $('#tag-' + id).attr('data-active');
            let res = [];
            if (state == 0) {
                $('#tag-' + id).addClass('active');
                $('#tag-' + id).attr('data-active', 1);
                selectedTags.push(name);
            } else {
                $('#tag-' + id).removeClass('active');
                $('#tag-' + id).attr('data-active', 0);
                res = selectedTags.filter((item) => {
                    return item != name;
                });
                selectedTags = res;
            }
        }

        function save() {
            let delta = quill.root.innerHTML;
            let form = $('#form_vacancy');
            let btn = $('#btn-save-vacancy');
            let method = form.attr('method');
            let url = form.attr('action');
            let publish = $('#publish').prop('checked') == false ? 0 : 1;
            $.ajax({
                type: method,
                url: url,
                data: {
                    title: $('#title').val(),
                    department: $('#department').val(),
                    division: $('#division').val(),
                    needs: $('#needs').val(),
                    dates: $('#dates').val(),
                    working_type: $('#working_type').val(),
                    job_type: $('#job_type').val(),
                    description: delta,
                    tags: selectedTags,
                    publish: publish
                },
                beforeSend: function() {
                    setLoading('btn-save-vacancy', true);
                },
                success: function(res) {
                    setLoading('btn-save-vacancy', false);
                    setNotif(false, res.message);
                    resetForm('form_vacancy');
                    quill.root.innerHTML = '';
                    clearSelectedTags();
                    $('#publish').prop('checked', false);
                },
                error: function(err) {
                    setLoading('btn-save-vacancy', false);
                    setNotif(true, 'Failed to save data');
                }
            })
        }
        
        function clearSelectedTags() {
            let elem = $('.tag');
            for (let a = 0; a < elem.length; a++) {
                elem[a].classList.remove('active');
            }
            selectedTags = [];
        }
    </script>
@endpush