<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;

class CustomerAddressPutRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'customer_address.province_id' => 'exists:m_provinces,province_id|nullable',
            'customer_address.city_id' => 'exists:m_cities,city_id|nullable',
            'customer_address.district_id' => 'exists:m_districts,district_id|nullable',
            'customer_address.village_id' => 'exists:m_villages,village_id|nullable',
            'customer_address.postalzip_id' => 'exists:m_postalzips,postalzip_id|nullable',
            'customer_address.address_street' => 'required|min:20',
            'customer_address.address_other' => 'nullable',
            "password" => "required",
        ];
    }

    public function attributes(): array
    {
        return [
            'customer_address.province_id' => 'Provinsi',
            'customer_address.city_id' => 'Kota / Kabupaten',
            'customer_address.district_id' => 'Kecamatan',
            'customer_address.village_id' => 'Keluharan / Desa',
            'customer_address.postalzip_id' => 'Kode Pos',
            'customer_address.address_street' => 'Alamat Lengkap',
            'customer_address.address_other' => 'Alamat Lainnya',
            'password' => 'Kata Sandi'
        ];
    }
}
