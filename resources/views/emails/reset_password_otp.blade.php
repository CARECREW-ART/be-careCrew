<x-mail::message>
    <p>Kepada, Bapak/Ibu</p>
    <p>Gunakan Kode OTP berikut untuk mengatur ulang kata sandi anda:
        <strong>{{ $otpCode }}</strong>
    </p>
    <p>Kode akan kadaluwarsa dalam 10 menit</p>
    <p>Jika ini bukan Anda, abaikan email ini. Mohon untuk tidak membalas email ini</p>
    <p>Terima Kasih,
        <strong>{{ config('app.name') }}</strong>
    </p>
</x-mail::message>
