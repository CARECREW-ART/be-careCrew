<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Service\Customer\CustomerService as CustomerCustomerService;
use App\Services\Customer\CustomerService;
use App\Services\User\UserService;
use Illuminate\Http\Request;

class CustomerController extends Controller
{

    // public function __construct(private CustomerCustomerService $customerService, private UserService $userService)
    // {
    //     $this->customerService = $customerService;
    //     $this->userService = $userService;   
    // }

    // public function createCustomer(CustomerPostRequest $req)
    // {
    //     $dataCustomerValidated = $req->validated();

    //     $this->customerService->createCustomer($dataCustomerValidated);

    //     return response()->json(
    //         [
    //             'message' => "Akun Customer Berhasil Dibuat",
    //         ], 201
    //     );
    // }

    public function postCustomer(Request $req) {
        return $req;
    }

    // public function getCustomerByUserId()
    // {
    //     $userId = auth('sanctum')->user()->user_id;

    //     $dataCustomer = $this->customerService->getCustomerByUserId($userId);

    //     return response()->json(['data' => $dataCustomer], 200);
    // }

    // public function getCustomer(Request $req)
    // {
    //     $data = $this->customerService->getCustomer($req['valueSearch'], $req['valueSort'], $req['sort']);

    //     return response()->json($data, 200);
    // }

    //public function getDetailCustomer($)
}
