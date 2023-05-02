<?php

namespace App\Models\Customer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MCustomer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'customer_fullname',
        'customer_nickname',
        'customer_username',
        'customer_telp',
        'customer_gender',
    ];

    protected $primaryKey ='customer_id';

    public function mCustomerAddress()
    {
        return $this->hasOne(MCustomerAddress::class,'customer_id','custumer_id');
    }

    public function mCustomerPicture()
    {
        return $this->hasOne(mCustomerPicture::class,'customer_id','custumer_id');
    }
}
