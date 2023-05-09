<?php

namespace App\Http\Requests\Assistant;

use Illuminate\Foundation\Http\FormRequest;

class AssistantAddressPutRequest extends FormRequest
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
            'assistant_address.province_id' => 'exists:m_provinces,province_id|nullable',
            'assistant_address.city_id' => 'exists:m_cities,city_id|nullable',
            'assistant_address.district_id' => 'exists:m_districts,district_id|nullable',
            'assistant_address.village_id' => 'exists:m_villages,village_id|nullable',
            'assistant_address.postalzip_id' => 'exists:m_postalzips,postalzip_id|nullable',
            'assistant_address.address_street' => 'required|min:20',
            'assistant_address.address_other' => 'nullable',
            "password" => "required",
        ];
    }

    public function attributes(): array
    {
        return [
            'assistant_address.province_id' => 'Provinsi',
            'assistant_address.city_id' => 'Kota / Kabupaten',
            'assistant_address.district_id' => 'Kecamatan',
            'assistant_address.village_id' => 'Keluharan / Desa',
            'assistant_address.postalzip_id' => 'Kode Pos',
            'assistant_address.address_street' => 'Alamat Lengkap',
            'assistant_address.address_other' => 'Alamat Lainnya',
            'password' => 'Kata Sandi'
        ];
    }
}
