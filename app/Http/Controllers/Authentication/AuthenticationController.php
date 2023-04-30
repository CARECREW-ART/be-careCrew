<?php

namespace App\Http\Controllers\Authentication;

use App\Http\Controllers\Controller;
use App\Http\Requests\Authentication\AuthenticationLoginRequest as AuthLogReq;
use App\Services\Authentication\AuthenticationService;
use App\Services\User\UserService;

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
}
