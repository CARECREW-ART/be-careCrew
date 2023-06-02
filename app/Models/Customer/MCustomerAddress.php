<?php

namespace App\Models\Customer;

use App\Models\Master\MCity;
use App\Models\Master\MDistrict;
use App\Models\Master\MPostalzip;
use App\Models\Master\MProvince;
use App\Models\Master\MVillage;
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

    public function mCustomerCity()
    {
        return $this->belongsTo(MCity::class, 'city_id', 'city_id');
    }

    public function mCustomerProvince()
    {
        return $this->belongsTo(MProvince::class, 'province_id', 'province_id');
    }

    public function mCustomerDistrict()
    {
        return $this->belongsTo(MDistrict::class, 'district_id', 'district_id');
    }

    public function mCustomerVillage()
    {
        return $this->belongsTo(MVillage::class, 'village_id', 'village_id');
    }

    public function mCustomerPostalZip()
    {
        return $this->belongsTo(MPostalzip::class, 'postalzip_id', 'postalzip_id');
    }
}
