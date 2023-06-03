<?php

namespace App\Models\Order;

use App\Models\Assistant\MAssistant;
use App\Models\Customer\MCustomer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'assistant_id',
        'customer_id',
        'total_price',
        'payment_status',
        'snap_token',
        'start_date',
        'end_date'
    ];

    protected $primaryKey = 'id';

    public function mAssistant()
    {
        return $this->hasOne(MAssistant::class, 'assistant_id', 'assistant_id');
    }

    public function mCustomer()
    {
        return $this->hasOne(MCustomer::class, 'customer_id', 'customer_id');
    }
}
