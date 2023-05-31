<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Services\Midtrans\CreateTransactionSnap;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function order(Request $req)
    {
        $midtrans = new CreateTransactionSnap(Str::random(12), 120000, "Adaapa dengan ini", "teser@tester.com", "08937438282");

        $paymentUrl = $midtrans->getSnapTransaction();

        return response()->json($paymentUrl);
    }
}
