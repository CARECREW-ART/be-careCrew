<?php

namespace App\Services\Order;

use App\Exceptions\CustomInvariantException;
use App\Exceptions\NotFoundException;
use App\Models\Order\Order;
use App\Services\Assistant\AssistantService;
use App\Services\Customer\CustomerService;
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
    public function __construct(private AssistantService $assistantService, private CustomerService $customerService)
    {
        $this->assistantService = $assistantService;
        $this->customerService = $customerService;
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

    public function changeBitActive($orderId, $value)
    {
        try {
            DB::beginTransaction();

            $dataOrder = Order::where("invoice_id", $orderId);
            $dataOrder->update(['bit_active' => $value]);

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

    public function getAllOrderByUserId($userId)
    {
        $dataCustomer = $this->customerService->getCustomerByUserId($userId);

        $dataOrder = Order::where('customer_id', $dataCustomer->customer_id)->with([
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
                    'assistant_username'
                );
            },
        ])->select(
            'id',
            'invoice_id',
            'assistant_id',
            'payment_status',
            'total_price',
            'snap_token'
        )->get();

        foreach ($dataOrder as $rQuery) {
            if ($rQuery['mAssistant']['mAssistantPicture'] == null) {
                continue;
            }
            $rQuery['mAssistant']['mAssistantPicture']['picture_path'] = Storage::url("/photoAssistant/" . $rQuery['mAssistant']['mAssistantPicture']['picture_filename']);
        }

        return $dataOrder;
    }

    public function assistantActiveOrder($userId)
    {
        $dataAssistant = $this->assistantService->getAssistantByUserId($userId);

        $dataOrder = Order::where('assistant_id', $dataAssistant->assistant_id)->with([
            'mCustomer' => function ($mCustomer) {
                $mCustomer->with([
                    'mCustomerPicture' => function ($CustomerPicture) {
                        $CustomerPicture->select(
                            'picture_id',
                            'customer_id',
                            'picture_filename',
                            'picture_path'
                        );
                    },
                ])->select(
                    'customer_id',
                    'user_id',
                    'customer_fullname',
                    'customer_nickname',
                );
            },
        ])->where('end_date', '>', date('Y-m-d'))->where('bit_active', true)->select(
            'id',
            'assistant_id',
            'customer_id',
            'start_date',
            'end_date'
        )->first();

        return $dataOrder;
    }

    public function assistantHistoryOrder($userId)
    {
        $dataAssistant = $this->assistantService->getAssistantByUserId($userId);

        $dataOrder = Order::where('assistant_id', $dataAssistant->assistant_id)->with([
            'mCustomer' => function ($mCustomer) {
                $mCustomer->with([
                    'mCustomerPicture' => function ($CustomerPicture) {
                        $CustomerPicture->select(
                            'picture_id',
                            'customer_id',
                            'picture_filename',
                            'picture_path'
                        );
                    },
                ])->select(
                    'customer_id',
                    'user_id',
                    'customer_fullname',
                    'customer_nickname',
                );
            },
        ])->where('payment_status', 'Success')->where('bit_active', false)->select(
            'id',
            'assistant_id',
            'customer_id',
            'start_date',
            'end_date'
        )->get();

        if (!count($dataOrder)) {
            throw new NotFoundException('data riwayat pekerjaan tidak ada');
        }

        return $dataOrder;
    }

    public function assistantActiveOrderDetail($userId)
    {
        return $this->customerService->getCustomerAndAddressByUserId($userId);
    }
}
