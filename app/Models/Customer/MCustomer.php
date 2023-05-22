<?php

namespace App\Models\Customer;

use App\Models\Master\MGender;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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
        return $this->hasOne(MGender::class, 'gender_bit', 'customer_gender');
    }

    public function emailUser()
    {
        return $this->hasOne(User::class, 'user_id', 'user_id');
    }

    public function mCustomerCity($customerId)
    {
        $data = DB::table('m_customers')->leftJoin(
            'm_customer_addresses',
            'm_customers.customer_id',
            '=',
            'm_customer_addresses.customer_id'
        )->leftJoin(
            'm_cities',
            'm_customer_addresses.city_id',
            '=',
            'm_cities.city_id'
        )->where(
            'm_customer_addresses.customer_id',
            '=',
            $customerId
        )->select(
            'm_customers.customer_id',
            'm_cities.city_id',
            'm_cities.city_name'
        )->first();

        return $data;
    }

    public function mCustomerProvince($customerId)
    {
        $data = DB::table('m_customers')->leftJoin(
            'm_customer_addresses',
            'm_customers.customer_id',
            '=',
            'm_customer_addresses.customer_id'
        )->leftJoin(
            'm_provinces',
            'm_customer_addresses.province_id',
            '=',
            'm_provinces.province_id'
        )->where(
            'm_customer_addresses.customer_id',
            '=',
            $customerId
        )->select(
            'm_customers.customer_id',
            'm_provinces.province_id',
            'm_provinces.province_name'
        )->first();

        return $data;
    }

    public function mCustomerDistrict($customerId)
    {
        $data = DB::table('m_customers')->leftJoin(
            'm_customer_addresses',
            'm_customers.customer_id',
            '=',
            'm_customer_addresses.customer_id'
        )->leftJoin(
            'm_districts',
            'm_customer_addresses.district_id',
            '=',
            'm_districts.district_id'
        )->where(
            'm_customer_addresses.customer_id',
            '=',
            $customerId
        )->select(
            'm_customers.customer_id',
            'm_districts.district_id',
            'm_districts.district_name'
        )->first();

        return $data;
    }

    public function mCustomerVillage($customerId)
    {
        $data = DB::table('m_customers')->leftJoin(
            'm_customer_addresses',
            'm_customers.customer_id',
            '=',
            'm_customer_addresses.customer_id'
        )->leftJoin(
            'm_villages',
            'm_customer_addresses.village_id',
            '=',
            'm_villages.village_id'
        )->where(
            'm_customer_addresses.customer_id',
            '=',
            $customerId
        )->select(
            'm_customers.customer_id',
            'm_villages.village_id',
            'm_villages.village_name'
        )->first();

        return $data;
    }

    public function mCustomerPostalZip($customerId)
    {
        $data = DB::table('m_customers')->leftJoin(
            'm_customer_addresses',
            'm_customers.customer_id',
            '=',
            'm_customer_addresses.customer_id'
        )->leftJoin(
            'm_postalzips',
            'm_customer_addresses.postalzip_id',
            '=',
            'm_postalzips.postalzip_id'
        )->where(
            'm_customer_addresses.customer_id',
            '=',
            $customerId
        )->select(
            'm_customers.customer_id',
            'm_postalzips.postalzip_id',
            'm_postalzips.postalzip_value'
        )->first();

        return $data;
    }
}
