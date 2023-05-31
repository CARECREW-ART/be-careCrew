<?php

namespace App\Models\Order;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'assistant_id',
        'customer_id',
        'total_price',
        'payment_status',
        'snap_token'
    ];

    protected $primaryKey = 'order_id';
}
