@extends('event::layouts.master')

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="text-end">
                <a href="{{ route('event.export-attendees', $id) }}" class="btn btn-primary"><i class="fa fa-file-export"></i> {{ __('event::view.export') }}</a>
            </div>

            {{-- divider --}}
            <div class="border-top mt-5"></div>

            <div class="table-responsive mt-5">
                <table class="table {{ dt_table_class() }}" id="table-detail-event-list">
                    <thead class="{{ dt_head_class() }}">
                        <tr>
                            <th>#</th>
                            <th>{{ __("event::view.name") }}</th>
                            <th>{{ __('attendant::view.employee_id') }}</th>
                            <th>{{ __('attendant::view.position') }}</th>
                            <th>{{ __('event::view.signature') }}</th>
                            <th>{{ __('event::view.checkin_at') }}</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ mix('dist/js/event.js') }}"></script>
    <script>
        $(document).ready(function() {
            initListAttendees("{{ $id }}");
        });
    </script>
@endpush