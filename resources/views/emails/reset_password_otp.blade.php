<x-mail::message>
    <p>Kepada, Bapak/Ibu

        Gunakan Kode OTP berikut untuk mengatur ulang kata sandi anda:
        <strong>{{ $otpCode }}</strong>

        Kode akan kadaluwarsa dalam 10 menit

        Jika ini bukan Anda, abaikan email ini. Mohon untuk tidak membalas email ini

        Terima Kasih,
        {{ config('app.name') }}
    </p>
</x-mail::message>
