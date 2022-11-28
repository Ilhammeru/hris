@extends('layouts.general-layout')

@section('content')
    <div class="text-center" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">
        <img src="{{ asset('checklist.png') }}" style="width: 70px; height: 70px;" alt="" class="checklist">

        <h3 class="mt-5">We have received your application, we will inform you of further information via email and whatsapp</h3>
    </div>
@endsection