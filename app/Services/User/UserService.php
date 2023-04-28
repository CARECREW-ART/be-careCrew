<?php

namespace App\Services\User;

use App\Models\User;
use Error;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpKernel\Exception\HttpException;

class UserService
{
    public function createUser($email, $password, $role)
    {
        $hashedPassword = Hash::make($password, ['rounds' => 12]);
        try {
            DB::beginTransaction();
            $dataUser = User::create([
                'email' => $email,
                'password' => $hashedPassword,
                'role' => strtoupper($role)
            ]);
            DB::commit();
            return $dataUser->user_id;
        } catch (Exception $e) {
            DB::rollBack();

            throw new HttpException(500, $e->getMessage());
        }
    }

    public function verifyUserCredentials($email, $password)
    {
        try {
            $user = User::where('email', $email)->firstOrFail();

            if (!strlen($user)) {
                return [false, "Alamat Email Tidak Ditemukan"];
            }

            $resultPassword = Hash::check($password, $user->password);

            if (!$resultPassword) {
                return [false, "Kredensial yang anda Berikan Salah"];
            }

            return [$user, 'success'];
        } catch (Exception $e) {
            throw new HttpException(500, $e->getMessage());
        }
    }

    public function deleteUser($userId)
    {
        try {
            DB::beginTransaction();

            $dataUser = User::findOrFail($userId);
            $dataUser->delete();

            DB::commit();
        } catch (ModelNotFoundException $e) {
            DB::rollBack();

            throw new HttpException(404, 'User Id ' . $userId . ' not Found');
        }
    }
}
