<?php

namespace App\Services\Order;

use App\Exceptions\CustomInvariantException;
use App\Models\Order\Order;
use App\Services\Assistant\AssistantService;
use App\Services\Midtrans\CreateTransactionSnap;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Storage;
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

    public function changePaymentType($orderId, $value)
    {
        try {
            DB::beginTransaction();

            $dataOrder = Order::where("invoice_id", $orderId);
            $dataOrder->update(['payment_type' => $value]);

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

    public function getOrderAdmin($valueSearch, $valueSort, $sort, $perPage)
    {
        $dataOrder = Order::with([
            'mAssistant' => function ($mAssistant) {
                $mAssistant->with([
                    'mAssistantPicture' => function ($assistantPicture) {
                        $assistantPicture->select(
                            'picture_id',
                            'assistant_id',
                            'picture_filename',
                            'picture_path'
                        );
                    },
                ])->select(
                    'assistant_id',
                    'assistant_fullname',
                    'assistant_nickname',
                );
            },
        ])->select(
            'id',
            'invoice_id',
            'assistant_id',
            'payment_status',
            'payment_type'
        )->where(function ($query) use ($valueSearch) {
            $query->where(
                'invoice_id',
                'LIKE',
                '%' . $valueSearch . '%'
            )->orWhere(
                'payment_status',
                'LIKE',
                '%' . $valueSearch . '%'
            )->orWhere(
                'payment_type',
                'LIKE',
                '%' . $valueSearch . '%'
            );
            return $query;
        });

        if (isset($valueSort) && isset($valueSort)) {
            $dataOrder = $dataOrder->orderBy($valueSort, $sort);
        }

        if (isset($perPage)) {
            $dataOrder = $dataOrder->latest()->paginate($perPage);
        }

        if ($perPage !== null) {
            $result = $dataOrder->appends(['sort' => $sort, 'valueSearch' => $valueSearch, 'valueSort' => $valueSort, 'perPage' => $perPage]);
            foreach ($result as $rQuery) {
                if ($rQuery['mAssistant']['mAssistantPicture'] == null) {
                    continue;
                }
                $rQuery['mAssistant']['mAssistantPicture']['picture_path'] = Storage::url("/photoAssistant/" . $rQuery['mAssistant']['mAssistantPicture']['picture_filename']);
            }
            return $result;
        }

        $result = $dataOrder->latest()->paginate(10)->appends(['sort' => $sort, 'valueSearch' => $valueSearch, 'valueSort' => $valueSort, 'perPage' => $perPage]);
        foreach ($result as $rQuery) {
            if ($rQuery['mAssistant']['mAssistantPicture'] == null) {
                continue;
            }
            $rQuery['mAssistant']['mAssistantPicture']['picture_path'] = Storage::url("/photoAssistant/" . $rQuery['mAssistant']['mAssistantPicture']['picture_filename']);
        }
        return $result;
    }
}
