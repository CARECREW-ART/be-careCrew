<?php

namespace App\Http\Requests\Assistant;

use Illuminate\Foundation\Http\FormRequest;

class AssistantPostRequest extends FormRequest
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
            'assistant.assistant_email' => 'required|email|unique:users,email',
            'assistant.assistant_password' => 'required|min:8|confirmed|regex:/^.*(?=.*?[A-Z])(?=.*[a-z])(?=.*[0-9]).*$/',
            'assistant.assistant_fullname' => 'required|string',
            'assistant.assistant_nickname' => 'required|string|max:50',
            'assistant.assistant_username' => 'required|min:6|max:20|alpha_num|unique:m_assistants,assistant_username',
            'assistant.assistant_telp' => 'required|max:16|unique:m_assistants,assistant_telp',
            'assistant.assistant_gender' => 'required|boolean',
            'assistant.assistant_birthdate' => [
                'required',
                'date',
                'date_format:Y-m-d',
                'before_or_equal:' . date("Y-m-d", strtotime("-18 year")),
                'after_or_equal:' . date("Y-m-d", strtotime("-55 year"))
            ],
            'assistant.assistant_salary' => 'required|numeric',
            'assistant.assistant_experience' => 'required|min:50',
            'assistant.assistant_isactive' => 'required|boolean',
            'assistant_address.province_id' => 'exists:m_provinces,province_id|nullable',
            'assistant_address.city_id' => 'exists:m_cities,city_id|nullable',
            'assistant_address.district_id' => 'exists:m_districts,district_id|nullable',
            'assistant_address.village_id' => 'exists:m_villages,village_id|nullable',
            'assistant_address.postalzip_id' => 'exists:m_postalzips,postalzip_id|nullable',
            'assistant_address.address_street' => 'required|min:20',
            'assistant_address.address_other' => 'nullable',
            'assistant.assistant_skills' => 'required|string',
            'assistant_accbank.bank_id' => 'required|exists:m_banks,bank_id',
            'assistant_accbank.accbank_name' => 'required|string',
            'assistant_accbank.accbank_value' => 'required|integer',
            'assistant_picture' => 'nullable|mimes:jpeg,jpg,png|min:30|max:10240'
        ];
    }

    public function attributes(): array
    {
        return [
            'assistant.assistant_email' => 'Alamat Email',
            'assistant.assistant_password' => 'Kata Sandi',
            'assistant.assistant_fullname' => 'Nama Lengkap',
            'assistant.assistant_nickname' => 'Nama Panggilan',
            'assistant.assistant_username' => 'Username',
            'assistant.assistant_telp' => 'Nomor Telepon',
            'assistant.assistant_gender' => 'Jenis Kelamin',
            'assistant.assistant_birthdate' => 'Tanggal Lahir',
            'assistant.assistant_salary' => 'Gaji',
            'assistant.assistant_experience' => 'Pengalaman',
            'assistant_address.province_id' => 'Provinsi',
            'assistant_address.city_id' => 'Kota / Kabupaten',
            'assistant_address.district_id' => 'Kecamatan',
            'assistant_address.village_id' => 'Keluharan / Desa',
            'assistant_address.postalzip_id' => 'Kode Pos',
            'assistant_address.address_street' => 'Alamat Lengkap',
            'assistant_address.address_other' => 'Alamat Lainnya',
            'assistant.assistant_skills' => 'Skill',
            'assistant_picture' => 'Foto Profil',
            'assistant_accbank.bank_id' => 'Bank',
            'assistant_accbank.accbank_name' => 'Nama Rekening',
            'assistant_accbank.accbank_value' => 'No. Rekening',
        ];
    }
}
