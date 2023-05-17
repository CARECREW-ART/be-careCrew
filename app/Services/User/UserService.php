<?php

namespace App\Services\User;

use App\Exceptions\CustomInvariantException;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Error;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class UserService
{
    public function createUser($email, $password, $role)
    {
        $hashedPassword = Hash::make($password, ['rounds' => 12]);
        try {
            DB::beginTransaction();
            $dataUser = User::create([
                'email' => strtolower($email),
                'password' => $hashedPassword,
                'role' => strtoupper($role)
            ]);
            DB::commit();
            return $dataUser->user_id;
        } catch (Exception $e) {
            DB::rollBack();

            throw new Exception($e->getMessage(), 500);
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
            throw new Exception($e->getMessage(), 500);
        }
    }

    public function verifyUserValidPassword($userId, $password)
    {
        try {
            $user = User::where('user_id', $userId)->first();

            $resultPassword = Hash::check($password, $user->password);

            if (!$resultPassword) {
                return [false, "Password yang Anda Masukkan Salah"];
            }

            return [$user->user_id, 'success'];
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), 500);
        }
    }

    public function changePasswordUser($userId, $newPassword, $oldPassword)
    {
        $hashedPassword = Hash::make($newPassword, ['rounds' => 12]);

        $user = User::where('user_id', $userId)->first();

        $resultPassword = Hash::check($oldPassword, $user->password);

        if (!$resultPassword) {
            throw new CustomInvariantException('Kata Sandi Lama Anda Tidak Sesuai');
        }

        try {
            DB::beginTransaction();
            $dataUser = User::where('user_id', $userId);
            $dataUser->update([
                'password' => $hashedPassword
            ]);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();

            throw new Exception($e->getMessage(), 500);
        }
    }

    // public function deleteUser($userId)
    // {
    //     try {
    //         DB::beginTransaction();

    //         $dataUser = User::findOrFail($userId);
    //         $dataUser->delete();

    //         DB::commit();
    //     } catch (ModelNotFoundException $e) {
    //         DB::rollBack();

    //         throw new HttpException(404, 'User Id ' . $userId . ' not Found');
    //     }
    // }
}
