<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Assistant\AssistantService;
use App\Services\Customer\CustomerService;
use App\Services\Order\OrderService;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Class constructor.
     */
    public function __construct(private AssistantService $assistantService, private CustomerService $customerService, private OrderService $orderService)
    {
        $this->assistantService = $assistantService;
        $this->customerService = $customerService;
        $this->orderService = $orderService;
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

    public function getAllOrderAdmin(Request $req)
    {
        $data = $this->orderService->getOrderAdmin($req['valueSearch'], $req['valueSort'], $req['sort'], $req['perPage']);

        return response()->json($data, 200);
    }
}
