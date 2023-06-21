<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Services\Assistant\AssistantService;
use App\Services\Customer\CustomerService;
use App\Services\Midtrans\CallbackService;
use App\Services\Order\OrderService;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;

class OrderController extends Controller
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

    public function createOrder(Request $req)
    {
        [$dataOrder, $paymentUrl] = $this->orderService->createOrder($req->only([
            'assistant_id',
            'assistant_fullname',
            'customer_id',
            'duration',
            'total_price',
            'customer_fullname',
            'customer_telp',
            'customer_email',
            'category'
        ]));

        return response()->json([
            'data' => [
                'order' => [
                    'order_id' => $dataOrder->invoice_id,
                    'total_price' => $dataOrder->total_price,
                    'payment_status' => $dataOrder->payment_status,
                    'start_date' => $dataOrder->start_date,
                    'end_date' => $dataOrder->end_date
                ],
                'midtrans' => $paymentUrl
            ]
        ], 201);
    }

    public function cancelOrder(Request $req)
    {
        $userId = auth('sanctum')->user()->user_id;
        $data = $this->orderService->cancelOrder($userId, $req->only(['token']));

        return response()->json(['data' => $data], 200);
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
                    'customer_email' => $dataCustomer->emailUser->email,
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

    public function orderNotification()
    {
        $callback = new CallbackService;

        if ($callback->isSignatureKeyVerified()) {
            $order = $callback->getOrder();
            $notification = $callback->getNotification();

            if ($callback->isSuccess()) {

                $this->orderService->changePaymentStatus($order->invoice_id, "Success");
                $this->orderService->changePaymentType($order->invoice_id, $notification->payment_type);
                $this->orderService->changeBitActive($order->invoice_id, true);
            }

            if ($callback->isPending()) {

                $this->orderService->changePaymentStatus($order->invoice_id, "Waiting For Payment");
                $this->orderService->changePaymentType($order->invoice_id, $notification->payment_type);
            }

            if ($callback->isExpire()) {
                $this->orderService->changePaymentStatus($order->invoice_id, "Expired");
                $this->orderService->changePaymentType($order->invoice_id, $notification->payment_type);
                $this->assistantService->putAssistantIsActive($order->assistant_id, 1);
            }

            if ($callback->isCancelled()) {
                $this->orderService->changePaymentStatus($order->invoice_id, "Canceled");
                $this->orderService->changePaymentType($order->invoice_id, $notification->payment_type);
                $this->assistantService->putAssistantIsActive($order->assistant_id, 1);
            }

            return response()
                ->json([
                    'success' => true,
                    'message' => 'Notifikasi berhasil diproses',
                ]);
        } else {
            return response()
                ->json([
                    'error' => true,
                    'message' => 'Signature key tidak terverifikasi',
                ], 403);
        }
    }

    public function getDetailOrderAdmin(Request $req)
    {
        $data = $this->orderService->getOrderDetail($req['order_id']);

        return response()->json(['data' => $data], 200);
    }

    public function getAllOrderByUserId()
    {
        $userId = auth('sanctum')->user()->user_id;

        $data = $this->orderService->getAllOrderByUserId($userId);

        return response()->json(['data' => $data]);
    }

    public function assistantActiveOrder()
    {
        $dateTime = new DateTime();
        $userId = auth('sanctum')->user()->user_id;

        $data = $this->orderService->assistantActiveOrder($userId);

        return response()->json([
            'data' => $data
        ]);
    }

    public function assistantHistoryOrder()
    {
        $userId = auth('sanctum')->user()->user_id;

        $data = $this->orderService->assistantHistoryOrder($userId);

        return response()->json(['data' => $data]);
    }

    public function assistantActiveOrderDetail(Request $req)
    {
        $data = $this->orderService->assistantActiveOrderDetail($req->only('customer_id'));

        return response()->json([
            'data' => $data
        ]);
    }

    public function changeStatusAssistantOrder(Request $req)
    {
        $userId = auth('sanctum')->user()->user_id;

        $this->orderService->changeStatusAssistantOrder($userId, $req->only('order_id'));

        return response()->json(['message' => 'Pekerjaan Selesai'], 200);
    }
}
