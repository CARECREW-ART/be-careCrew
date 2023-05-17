<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;

class CustomerPostRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'customer.customer_email' => 'required|email|unique:users,email',
            'customer.customer_password' => 'required|min:8|confirmed|regex:/^.*(?=.*?[A-Z])(?=.*[a-z])(?=.*[0-9]).*$/',
            'customer.customer_fullname' => 'required|string|max:160',
            'customer.customer_nickname' => 'required|string|max:40',
            'customer.customer_username' => 'required|unique:m_customers,customer_username',
            'customer.customer_telp' => 'required|string|max:16',
            'customer_address.province_id' => 'exists:m_provinces,province_id|',
            'customer_address.city_id' => 'exists:m_cities,city_id|',
            'customer_address.district_id' => 'exists:m_districts,district_id|nullable',
            'customer_address.village_id' => 'exists:m_villages,village_id|nullable',
            'customer_address.postalzip_id' => 'exists:m_postalzips,postalzip_id|nullable',
            'customer_address.address_street' => 'required|min:20',
            'customer_address.address_other' => 'nullable',
            'customer_picture' => 'nullable|mimes:jpeg,jpg,png|min:30|max:10240',
            'customer.customer_gender' => 'required|numeric|exists:m_genders,gender_bit',
            'customer.customer_birthdate' => [
                'required',
                'date',
                'date_format:Y-m-d',
                'before_or_equal:' . date("Y-m-d", strtotime("-18 year")),
                'after_or_equal:' . date("Y-m-d", strtotime("-70 year"))
            ],
        ];
    }

    public function attributes(): array
    {
        return [
            'customer.customer_email' => 'Alamat Email',
            'customer.customer_password' => 'Password',
            'customer.customer_fullname' => 'Customer Fullname',
            'customer.customer_nickname' => 'Customer Nickname',
            'customer.customer_username' => 'Customer Username',
            'customer.customer_telp' => 'Customer Telp',
            'customer_address.province_id' => 'Provinsi',
            'customer_address.city_id' => 'Kota / Kabupaten',
            'customer_address.district_id' => 'Kecamatan',
            'customer_address.village_id' => 'Kelurahan / Desa',
            'customer_address.postalzip_id' => 'Kode Pos',
            'customer_address.address_street' => 'Alamat Lengkap',
            'customer_address.address_other' => 'Alamat Lainnya',
            'customer.customer_gender' => 'Jenis Kelamin',
            'customer.customer_birthdate' => 'Tanggal lahir'
        ];
    }
}
