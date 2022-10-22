<link rel="stylesheet" href="{{ asset('css/iziToast.min.css') }}">
<script src="{{ asset('js/iziToast.min.js') }}"></script>
@if(session()->has('notify'))
    @foreach(session('notify') as $msg)
        @if (is_array($msg[1]))
            @for($a = 0; $a < count($msg[1]); $a++)
                <script> 
                    "use strict";
                    iziToast.{{ $msg[0] }}({message:"{{ __($msg[1][$a]) }}", position: "topRight"}); 
                </script>
            @endfor
        @else            
            <script> 
                "use strict";
                iziToast.{{ $msg[0] }}({message:"{{ __($msg[1]) }}", position: "topRight"}); 
            </script>
        @endif
    @endforeach
@endif

@if ($errors->any())
    @php
        $collection = collect($errors->all());
        $errors = $collection->unique();
    @endphp

    <script>
        "use strict";
        @foreach ($errors as $error)
        iziToast.error({
            message: '{{ __($error) }}',
            position: "topRight"
        });
        @endforeach
    </script>

@endif
<script>
    "use strict";
    function notify(status,message) {
        iziToast[status]({
            message: message,
            position: "topRight"
        });
    }
</script>
