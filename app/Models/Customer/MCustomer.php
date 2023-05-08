<?php

namespace App\Models\Customer;

use App\Models\Master\MGender;
use App\Models\User;
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
        'customer_birthdate'
    ];

    protected $primaryKey = 'customer_id';

    public function mCustomerAddress()
    {
        return $this->hasOne(MCustomerAddress::class, 'customer_id', 'customer_id');
    }

    public function mCustomerPicture()
    {
        return $this->hasOne(MCustormerPicture::class, 'customer_id', 'customer_id');
    }

    public function customerGender()
    {
        return $this->hasOne(MGender::class, 'gender_bit', 'assistant_gender');
    }

    public function emailUser()
    {
        return $this->hasOne(User::class, 'user_id', 'user_id');
    }
}
