<?php

namespace App\Services\Order;

use App\Exceptions\CustomInvariantException;
use App\Models\Order\Order;
use App\Services\Assistant\AssistantService;
use App\Services\Midtrans\CreateTransactionSnap;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Str;

class OrderService
{
    /**
     * Class constructor.
     */
    public function __construct(private AssistantService $assistantService)
    {
        $this->assistantService = $assistantService;
    }

    public function createOrder($payload)
    {
        $assistantId = $payload['assistant_id'];
        $customerId = $payload['customer_id'];
        $customerFullname = $payload['customer_fullname'];
        $customerEmail = $payload['customer_email'];
        $customerTelp = $payload['customer_telp'];
        $duration = $payload['duration'];
        $totalPrice = $payload['total_price'];

        $assistantIsActive = $this->assistantService->getAssistantIsActive($assistantId);

        if (!$assistantIsActive) {
            throw new CustomInvariantException("Assistant Tidak Dapat Di Order");
        }

        $orderId = date("ymd") . 'AST' . strtoupper(Str::random(5));
        $startDate = date("Y-m-d");
        $endDate = date("Y-m-d", strtotime("+" . $duration .  "month"));
        $midtrans = new CreateTransactionSnap($orderId, $totalPrice, $customerFullname, $customerEmail, $customerTelp);
        $paymentUrl = $midtrans->getSnapTransaction();

        try {
            DB::beginTransaction();

            $dataOrder = Order::create([
                'invoice_id' => $orderId,
                'assistant_id' => $assistantId,
                'customer_id' => $customerId,
                'total_price' => $totalPrice,
                'payment_status' => "Created",
                'snap_token' => $paymentUrl->token,
                'start_date' => $startDate,
                'end_date' => $endDate
            ]);

            $this->assistantService->putAssistantIsActive($assistantId, 0);
            DB::commit();

            return [$dataOrder, $paymentUrl];
        } catch (Exception $e) {
            DB::rollBack();

            throw new Exception($e->getMessage());
        }
    }

    public function changePaymentStatus($orderId, $value)
    {
        try {
            DB::beginTransaction();

            $dataOrder = Order::where("invoice_id", $orderId);
            $dataOrder->update(['payment_status' => $value]);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();

            throw new Exception($e->getMessage());
        }
    }

    public function getOrderDetail($orderId)
    {
        $dataOrder = Order::where('invoice_id', $orderId)->with([
            'mAssistant',
            'mCustomer'
        ])->first();

        return $dataOrder;
    }
}
