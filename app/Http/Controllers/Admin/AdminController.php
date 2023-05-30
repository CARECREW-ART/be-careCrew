<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Assistant\AssistantService;
use App\Services\Customer\CustomerService;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Class constructor.
     */
    public function __construct(private AssistantService $assistantService, private CustomerService $customerService)
    {
        $this->assistantService = $assistantService;
        $this->customerService = $customerService;
    }

    public function getAssistant(Request $req)
    {
        $data = $this->assistantService->getAssistantAdmin($req['valueSearch'], $req['valueSort'], $req['sort'], $req['perPage']);

        return response()->json($data, 200);
    }

    public function getAssistantDetail($userId)
    {
        $data = $this->assistantService->getAssistantByUserId($userId);

        return response()->json($data, 200);
    }

    public function getCustomer(Request $req)
    {
        $data = $this->customerService->getCustomerAdmin($req['valueSearch'], $req['valueSort'], $req['sort'], $req['perPage']);

        return response()->json($data, 200);
    }

    public function getCustomerDetail($userId)
    {
        $data = $this->customerService->getCustomerByUserId($userId);

        return response()->json($data, 200);
    }
}
