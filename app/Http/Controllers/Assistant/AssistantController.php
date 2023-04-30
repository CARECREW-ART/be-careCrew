<?php

namespace App\Http\Controllers\Assistant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Assistant\AssistantPostRequest;
use App\Services\Assistant\AssistantService;
use App\Services\User\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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
            ],
            201
        );
    }

    public function getAssistantByUserId()
    {
        $userId = auth('sanctum')->user()->user_id;

        $dataAssistant = $this->assistantService->getAssistantByUserId($userId);

        return response()->json(['data' => $dataAssistant], 200);
    }

    public function getAssistant(Request $req)
    {
        $data = $this->assistantService->getAssistant($req['valueSearch'], $req['valueSort'], $req['sort'], $req['perPage']);
        return response()->json($data, 200);
    }

    public function getDetailAssistant($username)
    {
        $data = $this->assistantService->getDetailAssistantById($username);
        return response()->json(['data' => $data], 200);
    }
}
