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

    public function __construct($orderId, $totalPrice, $customerName, $customerEmail, $customerPhone)
    {
        parent::__construct();

        $this->orderId = $orderId;
        $this->totalPrice = $totalPrice;
        $this->customerEmail = $customerEmail;
        $this->customerName = $customerName;
        $this->customerPhone = $customerPhone;
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

        $params = array(
            'transaction_details' => array(
                'order_id' => $this->orderId,
                'gross_amount' => $this->totalPrice,
            ),
            'customer_details' => array(
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $this->customerEmail,
                'phone' => $this->customerPhone,
            ),
        );

        $paymentUrl = Snap::createTransaction($params);

        return $paymentUrl;
    }
}