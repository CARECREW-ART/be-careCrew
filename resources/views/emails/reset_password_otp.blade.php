<x-mail::message>
    <p>Kepada, Bapak/Ibu<br></p>

    <p>Gunakan Kode OTP berikut untuk mengatur ulang kata sandi anda:</p>
    <p><strong>{{ $otpCode }}</strong></p>
    <p>Kode akan kadaluwarsa dalam 10 menit<br><br></p>

    <p>Jika ini bukan Anda, abaikan email ini. Mohon untuk tidak membalas email ini</p>

    <p>Terima Kasih,<br>
        {{ config('app.name') }}
    </p>
</x-mail::message>
