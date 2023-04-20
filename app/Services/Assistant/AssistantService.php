<?php

namespace App\Services\Assistant;

use App\Models\Assistant\MAssistant;
use App\Services\User\UserService;
use Illuminate\Support\Facades\DB;

class AssistantService
{
    /**
     * Class constructor.
     */

    public function __construct(private UserService $userService)
    {
        $this->userService = $userService;
    }

    public function createAssistant($dataAssistant)
    {
        return $dataAssistant;

        // $emailUser = $dataAssistant['assistant_email'];
        // $passwordUser = $dataAssistant['assistant_password'];

        // try {
        //     DB::beginTransaction();
        //     $userId = $this->userService->createUser($emailUser, $passwordUser);

        //     MAssistant::create([
        //         'user_id' => $userId,
        //         'assistant_fullname' => $dataAssistant['assistant_fullname'],
        //         'assistant_nickname' => $dataAssistant['assistant_nickname'],
        //         'assistant_username' => $dataAssistant['assistant_username'],
        //     ]);
        // } catch (\Throwable $th) {
        //     //throw $th;
        // }
    }
}
