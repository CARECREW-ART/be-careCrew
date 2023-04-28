<?php

namespace App\Services\Assistant;

use App\Models\Assistant\MAssistant;
use App\Models\Assistant\MAssistantAccbank;
use App\Models\Assistant\MAssistantAddress;
use App\Models\Assistant\MAssistantPicture;
use App\Models\Assistant\MAssistantSkill;
use App\Services\User\UserService;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use PDOException;
use Illuminate\Support\Str;

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
        $dataAssistantSkill = $data['assistant_skill'];
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
                'assistant_username' => $dataAssistant['assistant_username'],
                'assistant_telp' => $dataAssistant['assistant_telp'],
                'assistant_gender' => $dataAssistant['assistant_gender'],
                'assistant_birthdate' => $dataAssistant['assistant_birthdate'],
                'assistant_salary' => $dataAssistant['assistant_salary'],
                'assistant_experience' => $dataAssistant['assistant_experience'],
                'assistant_isactive' => $dataAssistant['assistant_isactive'],
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
                'bank_id' => $dataAssistantBank['bank_id']
            ]);

            foreach ($dataAssistantSkill as $dASkill) {
                MAssistantSkill::create([
                    'assistant_id' => $assistantId,
                    'skill_name' => $dASkill['skill_name']
                ]);
            }

            //Link Photo to Storage
            $photoNameExt = $profilePhoto->getClientOriginalName();
            $extention = $profilePhoto->extension();
            $file_name = (Str::random(16) . '.' . $extention);
            $path = $profilePhoto->move('./storage/photoAssistant', $file_name);

            MAssistantPicture::create([
                'assistant_id' => $assistantId,
                'picture_filename' => $file_name,
                'picture_imagename' => $photoNameExt,
                'picture_mime' => $extention,
                'picture_path' => $path
            ]);

            DB::commit();
            return [$assistantId];
        } catch (Exception $e) {
            DB::rollBack();

            if (isset($path)) {
                File::delete($path);
            }

            throw new Exception($e);
        }
    }

    public function getAssistantById($userId)
    {
    }
}
