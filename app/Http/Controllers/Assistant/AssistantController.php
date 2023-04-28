<?php

namespace App\Http\Controllers\Assistant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Assistant\AssistantPostRequest;
use App\Services\Assistant\AssistantService;
use App\Services\User\UserService;
use Illuminate\Http\Request;

class AssistantController extends Controller
{
    /**
     * Class constructor.
     */

    public function __construct(private AssistantService $assistantService, private UserService $userService)
    {
        $this->assistantService = $assistantService;
        $this->userService = $userService;
    }

    public function createAssistant(AssistantPostRequest $req)
    {
        $dataAssistantValidated = $req->validated();

        [$assistantId] = $this->assistantService->createAssistant($dataAssistantValidated);

        return response()->json(
            [
                'message' => 'Assistant Berhasil Dibuat',
                "data" => [
                    'assistantId' => $assistantId
                ]
            ],
            201
        );
    }

    public function deleteUser(Request $req)
    {
        return $this->userService->deleteUser($req['id']);
    }
}
