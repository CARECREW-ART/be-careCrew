<?php

namespace App\Services\Assistant;

use App\Exceptions\NotFoundException;
use App\Models\Assistant\AssistantFavorite;
use App\Models\Assistant\MAssistant;
use App\Models\Assistant\MAssistantAccbank;
use App\Models\Assistant\MAssistantAddress;
use App\Models\Assistant\MAssistantPicture;
use Illuminate\Support\Facades\Storage;
use App\Services\User\UserService;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\HttpException;

class AssistantService
{
    /**
     * Class constructor.
     */

    public function __construct(private UserService $userService)
    {
        $this->userService = $userService;
    }

    public function createAssistant($data)
    {
        $dataAssistant = $data['assistant'];
        $dataAssistantAddrs = $data['assistant_address'];
        $dataAssistantBank = $data['assistant_accbank'];
        $profilePhoto = $data['assistant_picture'];

        $emailUser = $dataAssistant['assistant_email'];
        $passwordUser = $dataAssistant['assistant_password'];

        try {
            DB::beginTransaction();

            $userId = $this->userService->createUser($emailUser, $passwordUser, 'assistant');

            $dataMAssistant = MAssistant::create([
                'user_id' => $userId,
                'assistant_fullname' => $dataAssistant['assistant_fullname'],
                'assistant_nickname' => $dataAssistant['assistant_nickname'],
                'assistant_username' => strtolower($dataAssistant['assistant_username']),
                'assistant_telp' => $dataAssistant['assistant_telp'],
                'assistant_gender' => $dataAssistant['assistant_gender'],
                'assistant_birthdate' => $dataAssistant['assistant_birthdate'],
                'assistant_salary' => $dataAssistant['assistant_salary'],
                'assistant_experience' => $dataAssistant['assistant_experience'],
                'assistant_isactive' => 1,
                'assistant_skills' => $dataAssistant['assistant_skills']
            ]);

            $assistantId = $dataMAssistant->assistant_id;

            MAssistantAddress::create([
                'assistant_id' => $assistantId,
                'province_id' => $dataAssistantAddrs['province_id'],
                'city_id' => $dataAssistantAddrs['city_id'],
                'district_id' => $dataAssistantAddrs['district_id'],
                'village_id' => $dataAssistantAddrs['village_id'],
                'postalzip_id' => $dataAssistantAddrs['postalzip_id'],
                'address_street' => $dataAssistantAddrs['address_street'],
                'address_other' => $dataAssistantAddrs['address_other']
            ]);

            MAssistantAccbank::create([
                'assistant_id' => $assistantId,
                'bank_id' => $dataAssistantBank['bank_id'],
                'accbank_name' => strtoupper($dataAssistantBank['accbank_name']),
                'accbank_value' => $dataAssistantBank['accbank_value']
            ]);

            //Link Photo to Storage
            $photoNameExt = $profilePhoto->getClientOriginalName();
            $extention = $profilePhoto->extension();
            $file_name = (Str::random(16) . '.' . $extention);
            $path = $profilePhoto->move('./storage/photoAssistant', $file_name);
            $url = Storage::url("/photoAssistant/" . $file_name);

            MAssistantPicture::create([
                'assistant_id' => $assistantId,
                'picture_filename' => $file_name,
                'picture_imagename' => $photoNameExt,
                'picture_mime' => $extention,
                'picture_path' => $url
            ]);

            DB::commit();
            return [$assistantId];
        } catch (Exception $e) {
            DB::rollBack();

            if (isset($path)) {
                File::delete($path);
            }

            throw new HttpException(500, $e->getMessage());
        }
    }

    public function getAssistantByUserId($userId)
    {
        $dataAssistant = MAssistant::where('user_id', $userId)->with([
            'assistantGender' => function ($assistantGender) {
                $assistantGender->select(
                    'gender_bit',
                    'gender_value'
                );
            },
            'mAssistantPicture' => function ($assistantPicture) {
                $assistantPicture->select(
                    'picture_id',
                    'assistant_id',
                    'picture_filename',
                    'picture_path'
                );
            },
            'mAssistantAddress' => function ($assistantAddress) {
                $assistantAddress->select(
                    'address_id',
                    'assistant_id',
                    'province_id',
                    'city_id',
                    'district_id',
                    'village_id',
                    'postalzip_id',
                    'address_street',
                    'address_other'
                );
            },
            'mAssistantAccbank' => function ($assistantBank) {
                $assistantBank->select(
                    'assistant_id',
                    'bank_id',
                    'accbank_name',
                    'accbank_value'
                );
            },
            'emailUser' => function ($email) {
                $email->select(
                    'user_id',
                    'email'
                );
            },
        ])->select(
            'assistant_id',
            'user_id',
            'assistant_fullname',
            'assistant_nickname',
            'assistant_username',
            'assistant_telp',
            'assistant_gender',
            'assistant_birthdate',
            'assistant_salary',
            'assistant_experience',
            'assistant_isactive',
        )->first();

        return $dataAssistant;
    }

    public function getAssistant($valueSearch, $valueSort, $sort, $perPage)
    {
        $dataAssistant = MAssistant::with([
            'mAssistantPicture' => function ($assistantPicture) {
                $assistantPicture->select(
                    'picture_id',
                    'assistant_id',
                    'picture_filename',
                    'picture_path'
                );
            },
        ])->select(
            'assistant_id',
            'assistant_fullname',
            'assistant_nickname',
            'assistant_username',
            'assistant_salary',
            'assistant_isactive'
        )->where(
            'assistant_isactive',
            '=',
            1
        )->where(function ($query) use ($valueSearch) {
            $query->where(
                'assistant_fullname',
                'LIKE',
                '%' . $valueSearch . '%'
            )->orWhere(
                'assistant_nickname',
                'LIKE',
                '%' . $valueSearch . '%'
            );
            return $query;
        });

        if (isset($valueSort) && isset($valueSort)) {
            $dataAssistant = $dataAssistant->orderBy($valueSort, $sort);
        }

        if (isset($perPage)) {
            $dataAssistant = $dataAssistant->latest()->paginate($perPage);
        }

        if ($perPage !== null) {
            $result = $dataAssistant->appends(['sort' => $sort, 'valueSearch' => $valueSearch, 'valueSort' => $valueSort, 'perPage' => $perPage]);
            return $result;
        }

        $result = $dataAssistant->latest()->paginate(10)->appends(['sort' => $sort, 'valueSearch' => $valueSearch, 'valueSort' => $valueSort, 'perPage' => $perPage]);
        return $result;
    }

    public function getDetailAssistantById($username)
    {
        $dataAssistant = MAssistant::with([
            'mAssistantPicture' => function ($assistantPicture) {
                $assistantPicture->select(
                    'picture_id',
                    'assistant_id',
                    'picture_filename',
                    'picture_path'
                );
            },
            'assistantGender' => function ($assistantGender) {
                $assistantGender->select(
                    'gender_bit',
                    'gender_value'
                );
            },
        ])->where(
            'assistant_username',
            '=',
            $username
        )->select(
            'assistant_id',
            'assistant_fullname',
            'assistant_nickname',
            'assistant_username',
            'assistant_gender',
            'assistant_birthdate',
            'assistant_salary',
            'assistant_experience',
            'assistant_skills',
            'assistant_isactive',
        )->first();

        if ($dataAssistant == null) {
            throw new NotFoundException("data Assistant tidak ada");
        }

        $dataCityAssistant = $dataAssistant->mAssistantCity($dataAssistant->assistant_id);

        $dataAssistant['city_name'] = $dataCityAssistant->city_name;

        return $dataAssistant;
    }

    public function postAsisstantFavoriteByUserId($username, $userId)
    {
        try {
            DB::beginTransaction();
            $assistantId = $this->getDetailAssistantById($username);

            $dataAssistantId = $this->getAssistantFavoriteByUserId($userId);

            foreach ($dataAssistantId as $aId) {
                if ($aId['assistant_id'] == $assistantId->assistant_id) {
                    return [false, "Data Assistant Favorite Sudah Ada"];
                }
            }

            AssistantFavorite::create([
                "user_id" => $userId,
                "assistant_id" => $assistantId->assistant_id
            ]);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }

    public function getAssistantFavoriteByUserId($userId)
    {
        $dataAssistant = AssistantFavorite::where('user_id', $userId)->with(
            [
                "mAssistant" => function ($mAssistant) {
                    $mAssistant->select(
                        'assistant_id',
                        'assistant_fullname',
                        'assistant_nickname',
                        'assistant_username',
                        'assistant_salary',
                        'assistant_isactive'
                    );
                }
            ],
        )->select('id', 'assistant_id', 'user_id')->get();

        foreach ($dataAssistant as $aId) {
            if ($aId['assistant_id'] == 12) {
                return true;
            }
        }

        return $dataAssistant;
    }

    public function putDetailAssistant($data, $userId)
    {
        try {
            DB::beginTransaction();
            $dataAssistant = MAssistant::where('user_id', $userId);
            $dataAssistant->update($data);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }

    public function putAssistantAddresByUserId($data, $userId)
    {
        try {
            DB::beginTransaction();
            $dataAssistantId = $this->getAssistantByUserId($userId);

            $dataAssistantAddrs = MAssistantAddress::where('assistant_id', $dataAssistantId->assistant_id);

            $dataAssistantAddrs->update($data);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }

    public function putAssistantBankByUserId($data, $userId)
    {
        try {
            DB::beginTransaction();
            $dataAssistantId = $this->getAssistantByUserId($userId);

            $dataAssistantBank = MAssistantAccbank::where('assistant_id', $dataAssistantId->assistant_id);

            $dataAssistantBank->update($data);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }
}
