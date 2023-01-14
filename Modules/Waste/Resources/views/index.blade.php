@php
    $ajax_url = route('setting.menu.ajax');
@endphp
@extends('waste::layouts.master')

@push('styles')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <style>
        .waste.tab-content {
            margin-top: 10px;
        }
    </style>
@endpush

@section('content')
    <div class="card">
        <div class="card-body">

            {{-- tabs --}}
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                  <button class="nav-link active" id="in-tab" data-bs-toggle="tab" data-bs-target="#in-tab-pane" type="button" role="tab" aria-controls="in-tab-pane" aria-selected="true">In</button>
                </li>
                <li class="nav-item" role="presentation">
                  <button class="nav-link" id="out-tab" data-bs-toggle="tab" data-bs-target="#out-tab-pane" type="button" role="tab" aria-controls="out-tab-pane" aria-selected="false">Out</button>
                </li>
            </ul>
            <div class="waste tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="in-tab-pane" role="tabpanel" aria-labelledby="in-tab" tabindex="0">
                    @include('waste::components.in')
                </div>
                <div class="tab-pane fade" id="out-tab-pane" role="tabpanel" aria-labelledby="out-tab" tabindex="0">
                    @include('waste::components.out')
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <script>
        $('#date_in_range').daterangepicker({
            autoUpdateInput: false,
            locale: {
                format: 'YYYY-MM-DD',
                cancelLabel: 'Clear'
            }
        }, (start, end, label) => {
        }).on('cancel.daterangepicker', function() {
            $('#date_start').val('');
            $('#date_end').val('');
        }).on('apply.daterangepicker', function(ev, picker) {
            $(this).val(
                picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-DD-MM')
            );
            $('#date_start').val(picker.startDate.format('YYYY-MM-DD'));
            $('#date_end').val(picker.endDate.format('YYYY-MM-DD'));
        });

        $('#date_exp_filter').daterangepicker({
            autoUpdateInput: false,
            locale: {
                format: 'YYYY-MM-DD',
                cancelLabel: 'Clear'
            }
        }).on('cancel.daterangepicker', function(ev, picker) {
            $('#date_exp_filter').val('');
            $('#exp_start').val('');
            $('#exp_end').val('');
        }).on('apply.daterangepicker', function(ev, picker) {
            $(this).val(
                picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-DD-MM')
            );
            $('#exp_start').val(picker.startDate.format('YYYY-MM-DD'));
            $('#exp_end').val(picker.endDate.format('YYYY-MM-DD'));
        });

        function selectAllCode(e) {
            let id = e.id;
            let checked = $('#' + id).prop('checked');
            let checks = $('.check-code');
            if (checked) {
                for (let a = 0; a < checks.length; a++) {
                    let select_id = checks[a].id;
                    $('#' + select_id).prop('checked', true);
                }
            } else {
                for (let a = 0; a < checks.length; a++) {
                    let select_id = checks[a].id;
                    $('#' + select_id).prop('checked', false);
                }
            }
        }

        function updateSelectAll(e) {
            let id = e.id;
            let checked = $('#' + id).prop('checked');
            let elems = $('.individual-code');
            if (!checked) {
                $('#code_all').prop('checked', false);
            }
            let selected = [];
            for (let a = 0; a < elems.length; a++) {
                let ids = elems[a].id;
                selected.push($('#' + ids).prop('checked'));
            }

            let check_all_elem = true;
            for (let b = 0; b < selected.length; b++) {
                if (selected[b] == false) {
                    check_all_elem = false;
                }
            }
            if (check_all_elem) {
                $('#code_all').prop('checked', true);
            }
        }
    </script>
@endpush