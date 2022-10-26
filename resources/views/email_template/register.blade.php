@extends('email_template.layout')
@section('content')
    <p>{{ __('user::users.register_greeting') }}, <strong>{{ $name }}</strong></p>
    <br />
    <p style="text-align:justify;">{{ __('user::users.register_success_welcome') }}</p>
    <br />
    <table style="width:100%;border-spacing:0;border-collapse:collapse;margin:0 auto">
        <tbody>
            <tr>
                <td style="width:120px;">{{ __('user::users.register_email') }}</td>
                <td style="width:10px;">:</td>
                <td><strong>{{ $email }}</strong></td>
            </tr>
            <tr>
                <td style="width:120px;">{{ __('user::users.register_password') }}</td>
                <td style="width:10px;">:</td>
                <td><strong>{{ $password }}</strong></td>
            </tr>
        </tbody>
    </table>
    <br />
    {{-- <p style="text-align:justify;"><a href="{{ route('login') }}">Klik disini</a> untuk menuju ke halaman Login. Ganti Password Anda ketika sudah login, dan jaga kerahasiaaan data akun login Anda agar tidak disalahgunakan.</p>
    <p style="text-align:justify;">Silahkan lakukan konfirmasi bahwa alamat email ini adalah benar milik Anda dengan mengklik link berikut ini:</p>
    <p style="text-align:justify;"><a href="{{ route('confirmEmail', ['username' => $username, 'code' => $code]) }}">{{ route('confirmEmail', ['username' => $username, 'code' => $code]) }}</a></p>
    <p style="text-align:justify;">Masa berlaku link akan berakhir pada <strong><?= date('d F Y', strtotime($expire)) ?></strong> pukul <strong><?= date('H:i', strtotime($expire)) ?> WIB</strong>.</p>
    <br /> --}}
    <br />
    <p>{{ __('user::users.register_thanks') }}.</p>
    <p><strong>HRIS System</strong></p>
@endsection