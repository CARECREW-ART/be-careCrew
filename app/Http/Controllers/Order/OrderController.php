<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Services\Assistant\AssistantService;
use App\Services\Customer\CustomerService;
use App\Services\Midtrans\CreateTransactionSnap;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    /**
     * Class constructor.
     */
    public function __construct(private AssistantService $assistantService, private CustomerService $customerService)
    {
        $this->assistantService = $assistantService;
        $this->customerService = $customerService;
    }

    public function order(Request $req)
    {
        $midtrans = new CreateTransactionSnap(Str::random(12), 120000, "Adaapa dengan ini", "teser@tester.com", "08937438282");

        $paymentUrl = $midtrans->getSnapTransaction();

        return response()->json($paymentUrl);
    }

    public function confirmOrder(Request $req)
    {
        $userId = auth('sanctum')->user()->user_id;
        $dataAssistant = $this->assistantService->getDetailAssistantById($req['username']);
        $dataCustomer = $this->customerService->getCustomerByUserId($userId);
        $dataCustomerAddress = $this->customerService->getCustomerAddressDetail($userId);

        return response()->json([
            'data' => [
                'assistant' => [
                    'assistant_id' => $dataAssistant->assistant_id,
                    'assistant_fullname' => $dataAssistant->assistant_fullname,
                    'assistant_birthdate' => $dataAssistant->assistant_birthdate,
                    'assistant_salary' => $dataAssistant->assistant_salary,
                    'assistant_gender' => $dataAssistant->assistantGender
                ],
                'customer' => [
                    'customer_id' => $dataCustomer->customer_id,
                    'customer_fullname' => $dataCustomer->customer_fullname,
                    'customer_telp' => $dataCustomer->customer_telp,
                    'customer_address' => [
                        'province_name' => $dataCustomerAddress->mCustomerAddress->mCustomerProvince->province_name,
                        'city_name' => $dataCustomerAddress->mCustomerAddress->mCustomerCity->city_name,
                        'district_name' => $dataCustomerAddress->mCustomerAddress->mCustomerDistrict->district_name,
                        'village_name' => $dataCustomerAddress->mCustomerAddress->mCustomerVillage->village_name,
                        'postalzip_value' => ($dataCustomerAddress->mCustomerAddress->mCustomerPostalzip == null) ? null : $dataCustomerAddress->mCustomerAddress->mCustomerPostalzip->postalzip_value,
                        'address_street' => $dataCustomerAddress->mCustomerAddress->address_street,
                        'address_other' => $dataCustomerAddress->mCustomerAddress->address_other,
                    ],
                ]
            ]
        ]);
    }
}
