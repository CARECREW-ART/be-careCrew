<?php

namespace App\Services\User;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function createUser($email, $password, $role)
    {
        $hashedPassword = Hash::make($password, ['rounds' => 12]);
        try {
            DB::beginTransaction();
            $dataUser = User::create([
                'email' => $email,
                'password' => $hashedPassword
            ]);
            DB::commit();
            return $dataUser->id;
        } catch (Exception $e) {
            DB::rollBack();

            return $e;
        }
    }
}
