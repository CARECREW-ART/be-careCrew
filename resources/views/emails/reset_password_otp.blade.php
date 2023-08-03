<x-mail::message>

    <p>Use the following OTP code to reset your password:</p>
    <p><strong>{{ $otpCode }}</strong></p>

    Thanks,
    {{ config('app.name') }}
</x-mail::message>
