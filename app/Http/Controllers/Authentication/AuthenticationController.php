<?php

namespace App\Http\Controllers\Authentication;

use App\Http\Controllers\Controller;
use App\Http\Requests\Authentication\AuthenticationLoginRequest as AuthLogReq;
use App\Http\Requests\Authentication\ResetPasswordRequest;
use App\Mail\ResetPasswordOTP;
use App\Services\Authentication\AuthenticationService;
use App\Services\User\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use PragmaRX\Google2FA\Google2FA;

class AuthenticationController extends Controller
{
    /**
     * Class constructor.
     */
    public function __construct(
        private UserService $userService,
        private AuthenticationService $authenticationService,
    ) {
        $this->userService = $userService;
        $this->authenticationService = $authenticationService;
    }

    public function login(AuthLogReq $req)
    {
        $dataValidReq = $req->validated();

        [$user, $message] = $this->userService->verifyUserCredentials($dataValidReq['email'], $dataValidReq['password']);

        if (!$user) {
            return response()->json(['message' => $message], 400);
        }

        [$token] = $this->authenticationService->createTokenUser($user);

        return response()->json(
            [
                "data" => [
                    "user_id" => $user->user_id,
                    "user_email" => $user->email,
                    "user_role" => $user->role,
                ],
                "access_token" => $token,
                "token_type" => "Bearer"
            ],
            201
        );
    }

    public function logout()
    {
        $user = auth('sanctum')->user();
        [$message] = $this->authenticationService->deleteTokenUser($user);

        return response()->json(['message' => $message], 200);
    }

    public function sendLinkOTP(Request $req)
    {
        $req->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        $user = $this->userService->userExistByEmail($req->email);

        $otpCode = random_int(000000, 999999);

        $bool = $this->userService->storeOTPCode($otpCode, $user->email);

        if ($bool == true) Mail::to($user->email)->send(new ResetPasswordOTP($otpCode));

        return response()->json(['message' => 'OTP code sent to your email.'], 200);
    }

    public function resetPassword(ResetPasswordRequest $req)
    {
        $dataValidReq = $req->validated();

        $user = $this->userService->userExistByEmail($dataValidReq['email']);

        $otp = $this->userService->getOTPCodeByEmail($user->email);

        if (!(Hash::check($dataValidReq['otp_code'], $otp->otp_code)  || now()->gte($otp->expire_otp_code))) {
            return response()->json(['message' => 'Invalid OTP code.'], 422);
        }

        $bool = $this->userService->storeNewPasswordUser($user->email, $dataValidReq['password']);

        if ($bool == true) {
            $this->userService->changeOTPExpireToNull($user->email);
            return response()->json(['message' => 'Password reset successful.'], 200);
        }

        return response()->json(['message' => 'Error'], 500);
    }
}
