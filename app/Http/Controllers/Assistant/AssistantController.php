<?php

namespace App\Http\Controllers\Assistant;

use App\Http\Controllers\Controller;
use App\Services\Assistant\AssistantService;
use Illuminate\Http\Request;

class AssistantController extends Controller
{
    /**
     * Class constructor.
     */

    public function __construct(private AssistantService $assistantService)
    {
        $this->assistantService = $assistantService;
    }

    public function createAssistant(Request $req)
    {
        $dataAssistantValidated = $req['assistant'];
        return $this->assistantService->createAssistant($dataAssistantValidated);
    }
}
