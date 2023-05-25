<?php

namespace App\Services\Assistant;

use App\Exceptions\CustomInvariantException;
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
            $extension = $profilePhoto->extension();
            $file_name = (Str::random(16) . '.' . $extension);
            $path = $profilePhoto->move('storage\photoAssistant', $file_name);

            MAssistantPicture::create([
                'assistant_id' => $assistantId,
                'picture_filename' => $file_name,
                'picture_imagename' => $photoNameExt,
                'picture_mime' => $extension,
                'picture_path' => $path
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
            'assistant_skills',
            'assistant_experience',
            'assistant_isactive',
        )->first();

        $dataAssistant['mAssistantPicture']['picture_path'] = Storage::url("/photoAssistant/" . $dataAssistant['mAssistantPicture']['picture_filename']);

        if ($dataAssistant == null) {
            throw new NotFoundException('Data Assistant Tidak Ada');
        }

        return $dataAssistant;
    }

    public function getAssistantBankByUserId($userId)
    {
        $dataAssistant = MAssistant::where('user_id', $userId)->first();

        if ($dataAssistant == null) {
            throw new NotFoundException('Data Assistant Tidak Ada');
        }

        $dataBank = $dataAssistant->mAssistantBankName($dataAssistant->assistant_id);

        return $dataBank;
    }

    public function getAssistantAddressByUserId($userId)
    {
        $dataAssistant = MAssistant::where('user_id', $userId)->with([
            'mAssistantAddress' => function ($assistantAddress) {
                $assistantAddress->select(
                    'address_id',
                    'assistant_id',
                    'address_street',
                    'address_other'
                );
            },
        ])->first('assistant_id');

        if ($dataAssistant == null) {
            throw new NotFoundException('Data Assistant Tidak Ada');
        }

        $dataCity = $dataAssistant->mAssistantCity($dataAssistant->assistant_id);
        $dataProvince = $dataAssistant->mAssistantProvince($dataAssistant->assistant_id);
        $dataDistrict = $dataAssistant->mAssistantDistrict($dataAssistant->assistant_id);
        $dataVillage = $dataAssistant->mAssistantVillage($dataAssistant->assistant_id);
        $dataPostalZip = $dataAssistant->mAssistantPostalZip($dataAssistant->assistant_id);

        $dataAssistant['m_city'] = $dataCity;
        $dataAssistant['m_province'] = $dataProvince;
        $dataAssistant['m_district'] = $dataDistrict;
        $dataAssistant['m_village'] = $dataVillage;
        $dataAssistant['m_postalzip'] = $dataPostalZip;

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
            foreach ($result as $rQuery) {
                if ($rQuery['mAssistantPicture'] == null) {
                    continue;
                }
                $rQuery['mAssistantPicture']['picture_path'] = Storage::url("/photoAssistant/" . $rQuery['mAssistantPicture']['picture_filename']);
            }
            return $result;
        }

        $result = $dataAssistant->latest()->paginate(10)->appends(['sort' => $sort, 'valueSearch' => $valueSearch, 'valueSort' => $valueSort, 'perPage' => $perPage]);
        foreach ($result as $rQuery) {
            if ($rQuery['mAssistantPicture'] == null) {
                continue;
            }
            $rQuery['mAssistantPicture']['picture_path'] = Storage::url("/photoAssistant/" . $rQuery['mAssistantPicture']['picture_filename']);
        }
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

        $dataAssistant['mAssistantPicture']['picture_path'] = Storage::url("/photoAssistant/" . $dataAssistant['mAssistantPicture']['picture_filename']);

        return $dataAssistant;
    }

    public function postAssistantFavoriteByUserId($username, $userId)
    {
        $assistantId = $this->getDetailAssistantById($username);

        $dataAssistantId = $this->getAssistantFavoriteByUserId($userId);

        foreach ($dataAssistantId as $aId) {
            if (($aId['assistant_id'] == $assistantId->assistant_id) && ($aId['user_id'] == $userId)) {
                throw new CustomInvariantException("Data Assistant Favorite Sudah Ada");
            }
        }
        try {
            DB::beginTransaction();

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

    public function deleteAssistantFavoriteByUserId($username, $userId)
    {
        $assistantId = $this->getDetailAssistantById($username);

        try {
            DB::beginTransaction();
            $dataAssistantFavorite = AssistantFavorite::where('assistant_id', $assistantId->assistant_id)->where('user_id', $userId);

            $dataAssistantFavorite->delete();
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
                },
                "mAssistantPicture" => function ($mAssistantPicture) {
                    $mAssistantPicture->select(
                        'picture_filename',
                        'picture_imagename',
                        'picture_mime',
                        'picture_path'
                    );
                },
            ],
        )->select('id', 'assistant_id', 'user_id')->get();

        foreach ($dataAssistant as $dAI) {
            if ($dAI['mAssistantPicture'] == null) {
                continue;
            }
            $dAI['mAssistantPicture']['picture_path'] = Storage::url("/photoAssistant/" . $dAI['mAssistantPicture']['picture_filename']);
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

    public function putAssistantPicture($data, $userId)
    {
        $dataAssistant = $this->getAssistantByUserId($userId);

        if ($dataAssistant == null) {
            throw new NotFoundException('data assistant tidak ada');
        }

        $dataAssistantPicture = $dataAssistant->mAssistantPicture;

        $pathOldPhoto = './storage/photoAssistant/' . $dataAssistantPicture->picture_filename;

        try {
            DB::beginTransaction();

            //Link Photo to Storage
            $dataPhoto = $data;
            $photoNameExt = $dataPhoto->getClientOriginalName();
            $extension = $dataPhoto->extension();
            $file_name = (Str::random(16) . '.' . $extension);
            $path = $dataPhoto->move('storage\photoAssistant', $file_name);

            MAssistantPicture::create([
                'assistant_id' => $dataAssistant->assistant_id,
                'picture_filename' => $file_name,
                'picture_imagename' => $photoNameExt,
                'picture_mime' => $extension,
                'picture_path' => $path
            ]);

            if (isset($pathOldPhoto)) {
                File::delete($pathOldPhoto);
            }

            $dataOldPhoto = MAssistantPicture::where('picture_filename', $dataAssistantPicture->picture_filename);
            $dataOldPhoto->delete();

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();

            if (isset($path)) {
                File::delete($path);
            }

            throw new HttpException(500, $e->getMessage());
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
