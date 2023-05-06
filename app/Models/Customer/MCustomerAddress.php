<?php

namespace App\Models\Customer;

use App\Models\Master\MCity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MCustomerAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'province_id',
        'city_id',
        'district_id',
        'village_id',
        'postalzip_id',
        'address_street',
        'address_other'
    ];
}
