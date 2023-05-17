<?php

namespace App\Http\Controllers\Assistant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Assistant\AssistantAddressPutRequest as AssistantAddrsPutReq;
use App\Http\Requests\Assistant\AssistantBankPutRequest;
use App\Http\Requests\Assistant\AssistantFavoritePostRequest as AssistantFavPostReq;
use App\Http\Requests\Assistant\AssistantPicturePutRequest;
use App\Http\Requests\Assistant\AssistantPostRequest;
use App\Http\Requests\Assistant\AssistantPutRequest;
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

    public function putAssistantByUserId(AssistantPutRequest $req)
    {
        $userId = auth('sanctum')->user()->user_id;

        $dataAssistant = $req->validated('assistant');

        [$data, $message] = $this->userService->verifyUserValidPassword($userId, $req['password']);

        if (!$data) {
            return response()->json(['message' => $message], 400);
        }
        $this->assistantService->putDetailAssistant($dataAssistant, $data);
        return response()->json(['message' => 'data berhasil diupdate'], 200);
    }

    public function putAssistantAddresByUserId(AssistantAddrsPutReq $req)
    {
        $userId = auth('sanctum')->user()->user_id;

        $dataAssistantAddress = $req->validated('assistant_address');

        [$data, $message] = $this->userService->verifyUserValidPassword($userId, $req['password']);

        if (!$data) {
            return response()->json(['message' => $message], 400);
        }

        $this->assistantService->putAssistantAddresByUserId($dataAssistantAddress, $userId);

        return response()->json(['message' => 'Data Alamat Berhasil diupdate'], 200);
    }

    public function putAssistantBankByUserId(AssistantBankPutRequest $req)
    {
        $userId = auth('sanctum')->user()->user_id;

        $dataBank = $req->validated('assistant_accbank');

        [$data, $message] = $this->userService->verifyUserValidPassword($userId, $req['password']);

        if (!$data) {
            return response()->json(['message' => $message], 400);
        }

        $data = $this->assistantService->putAssistantBankByUserId($dataBank, $userId);

        return response()->json(['message' => 'Data Bank Berhasil diupdate'], 200);
    }

    public function putAssistantPictureByUserId(AssistantPicturePutRequest $req)
    {
        $userId = auth('sanctum')->user()->user_id;

        $dataValidated = $req->validated();

        $this->assistantService->putAssistantPicture($dataValidated['assistant_picture'], $userId);

        return response()->json(['message' => 'foto profile berhasil diperbaharui'], 201);
    }

    public function postAssistantFavoriteByUserId(AssistantFavPostReq $req)
    {
        $userId = auth('sanctum')->user()->user_id;

        $usernameValidated = $req->validated('assistant_username');

        [$data, $message] = $this->assistantService->postAsisstantFavoriteByUserId($usernameValidated, $userId);

        if (!$data) {
            return response()->json(['message' => $message], 200);
        }

        return response()->json(['message' => "Data Assistant Berhasil ditambahkan ke Favorite"], 201);
    }

    public function getAssistantFavoriteByUserId()
    {
        $userId = auth('sanctum')->user()->user_id;

        $data = $this->assistantService->getAssistantFavoriteByUserId($userId);

        return response()->json(['data' => $data], 200);
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
