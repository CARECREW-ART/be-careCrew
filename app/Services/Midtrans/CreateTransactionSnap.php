<?php

namespace App\Services\Midtrans;

use Midtrans\Snap;

class CreateTransactionSnap extends Midtrans
{
    protected $orderId;
    protected $totalPrice;
    protected $customerName;
    protected $customerEmail;
    protected $customerPhone;
    protected $assistantId;
    protected $duration;
    protected $category;
    protected $assistantFullname;
    protected $midtrans;

    public function __construct($orderId, $totalPrice, $customerName, $customerEmail, $customerPhone, $assistantId, $duration, $category, $assistantFullname)
    {
        $this->midtrans = parent::__construct();

        $this->orderId = $orderId;
        $this->totalPrice = $totalPrice;
        $this->customerEmail = $customerEmail;
        $this->customerName = $customerName;
        $this->customerPhone = $customerPhone;
        $this->assistantId = $assistantId;
        $this->duration = $duration;
        $this->category = $category;
        $this->assistantFullname = $assistantFullname;
    }

    public function getSnapTransaction()
    {
        $fullName = $this->customerName;

        //Separate FirstName LastName
        $fullNameSplit = explode(" ", $fullName);
        if (count($fullNameSplit) > 1) {
            $lastName = array_pop($fullNameSplit);
            $firstName = implode(" ", $fullNameSplit);
        } else {
            $firstName = $fullName;
            $lastName = " ";
        }

        //price
        $price = ($this->totalPrice / $this->duration);

        $params = [
            'transaction_details' => [
                'order_id' => $this->orderId,
                'gross_amount' => $this->totalPrice,
            ],
            "item_details" => [[
                "id" => $this->assistantId,
                "price" => $price,
                "quantity" => 1,
                "name" => "({$this->category}) " . $this->assistantFullname,
                "brand" => "CareCrew",
                "category" => $this->category,
                "merchant_name" => "CareCrew"
            ]],
            'customer_details' => [
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $this->customerEmail,
                'phone' => $this->customerPhone,
            ],
            "expiry" => [
                "unit" => "minutes",
                "duration" => 30
            ],
        ];

        $paymentUrl = Snap::createTransaction($params);

        return $paymentUrl;
    }

    public function a()
    {
        Midtrans::cancelTransaction("ss");
    }
}
