<x-mail::message>
    <p>Kepada, Bapak/Ibu</p>
    <p>Gunakan Kode OTP berikut untuk mengatur ulang kata sandi anda:</p>
    <p><strong>{{ $otpCode }}</strong></p>
    <p>Kode akan kadaluwarsa dalam 10 menit</p>
    <p>Jika anda bukan yang meminta pengaturan ulang kata sandi, mohon abaikan email ini.</p>
    <p>Mohon untuk tidak membalas email ini.</p>
    <p>Terima Kasih,</p>
    <p><strong>{{ config('app.name') }}</strong></p>
</x-mail::message>
