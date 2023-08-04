<?php

namespace App\Services\User;

use App\Exceptions\CustomInvariantException;
use App\Exceptions\NotFoundException;
use App\Models\PasswordResetToken;
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

            throw new Exception($e->getMessage());
        }
    }

    public function verifyUserCredentials($email, $password)
    {
        $user = User::where('email', $email)->first();

        if ($user == null) {
            throw new NotFoundException("Data User Tidak Ditemukan");
        }

        try {
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
        $user = User::where('user_id', $userId)->first();

        if ($user == null) {
            throw new NotFoundException("Data User Tidak Ditemukan");
        }

        try {
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

        if ($user == null) {
            throw new NotFoundException("Data User Tidak Ditemukan");
        }

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

    public function userExistByEmail($email)
    {
        $user = User::where('email', $email)->first();

        if ($user == null) {
            throw new NotFoundException("Data User Tidak Ditemukan");
        }

        return $user;
    }

    public function storeOTPCode($otpCode, $email)
    {
        try {
            DB::beginTransaction();

            $otp = PasswordResetToken::where('email', $email)->first();

            if ($otp !== null) {
                $otp->update([
                    'otp_code' => Hash::make($otpCode, ['rounds' => 12]),
                    'expire_otp_code' => now()->addMinutes(10)
                ]);
            }

            PasswordResetToken::create([
                'email' => $email,
                'otp_code' => Hash::make($otpCode, ['rounds' => 12]),
                'expire_otp_code' => now()->addMinutes(10)
            ]);

            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();

            throw new Exception($e->getMessage(), 500);
        }
    }

    public function getOTPCodeByEmail($email)
    {
        $otp = PasswordResetToken::where('email', $email)->first();

        if ($otp == null) {
            throw new NotFoundException("OTP Tidak Ditemukan");
        }

        return $otp;
    }

    public function storeNewPasswordUser($email, $password)
    {
        $hashedPassword = Hash::make($password, ['rounds' => 12]);

        try {
            DB::beginTransaction();
            $dataUser = User::where('email', $email);
            $dataUser->update([
                'password' => $hashedPassword
            ]);
            DB::commit();

            return true;
        } catch (Exception $e) {
            DB::rollBack();

            throw new Exception($e->getMessage(), 500);
        }
    }

    public function changeOTPExpireToNull($email)
    {
        try {
            DB::beginTransaction();
            $otp = PasswordResetToken::where('email', $email);
            $otp->delete();

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();

            throw new Exception($e->getMessage(), 500);
        }
    }
}
