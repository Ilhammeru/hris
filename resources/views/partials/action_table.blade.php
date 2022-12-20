@php
    $type = isset($btn_type) ? $btn_type : 'button';
    $elem_type_fix = isset($elem_type) ? $elem_type : 'button';
@endphp

@if ($elem_type_fix == 'button')
    <button class="btn btn-{{ $color }} btn-sm {{ isset($custom_class) ? $custom_class : '' }}"
        type="{{ $type }}"
        @if(isset($onClick))onclick="{{$onClick}}({{ $paramOnClick ?? '' }})"@endif>
        {!! $text !!}
    </button>
@endif