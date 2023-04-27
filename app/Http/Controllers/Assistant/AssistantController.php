<?php

namespace App\Http\Controllers\Assistant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Assistant\AssistantPostRequest;
use App\Services\Assistant\AssistantService;

class AssistantController extends Controller
{
    /**
     * Class constructor.
     */

    public function __construct(private AssistantService $assistantService)
    {
        $this->assistantService = $assistantService;
    }

    public function createAssistant(AssistantPostRequest $req)
    {
        $dataAssistantValidated = $req->validated();
        return $dataAssistantValidated;
    }
}
