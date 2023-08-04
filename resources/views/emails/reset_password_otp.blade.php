<x-mail::message>
    <p>Kepada, Bapak/Ibu</p>
    <br>

    <p>Gunakan Kode OTP berikut untuk mengatur ulang kata sandi anda:</p>
    <p><strong>{{ $otpCode }}</strong></p>
    <p>Kode akan kadaluwarsa dalam 10 menit</p>
    <br>
    <br>

    <p>Jika ini bukan Anda, abaikan email ini. Mohon untuk tidak membalas email ini</p>

    <p>Terima Kasih,<br>
        {{ config('app.name') }}
    </p>
</x-mail::message>
