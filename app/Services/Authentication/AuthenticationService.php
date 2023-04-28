<?php

namespace App\Services\Authentication;

class AuthenticationService
{
    public function createTokenUser($user)
    {
        $token = $user->createToken('auth-token')->plainTextToken;

        return [$token];
    }

    public function deleteTokenUser($user)
    {
        $user->tokens()->delete();

        return ["User Berhasil Logout"];
    }
}
