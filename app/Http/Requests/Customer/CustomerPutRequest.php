<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;

class CustomerPutRequest extends FormRequest
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
            "customer.customer_fullname" => "required|string",
            "customer.customer_nickname" => "required|string|max:50",
            "customer.customer_telp" => "required|max:16",
            "customer.customer_gender" => "required|integer",
            "customer.customer_birthdate" => [
                'required',
                'date',
                'date_format:Y-m-d',
                'before_or_equal:' . date("Y-m-d", strtotime("-18 year")),
                'after_or_equal:' . date("Y-m-d", strtotime("-55 year"))
            ],
            "password" => "required",
        ];
    }

    public function attributes(): array
    {
        return [
            'customer.customer_fullname' => 'Nama Lengkap',
            'customer.customer_nickname' => 'Nama Panggilan',
            'customer.customer_telp' => 'Nomor Telepon',
            'customer.customer_gender' => 'Jenis Kelamin',
            'customer.customer_birthdate' => 'Tanggal Lahir',
            'password' => 'Kata Sandi'
        ];
    }
}
