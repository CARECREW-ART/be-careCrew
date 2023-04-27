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
            'assistant.assistant_username' => 'required|min:6|max:20|unique:m_assistants,assistant_username',
            'assistant.assistant_telp' => 'required|max:16',
            'assistant.assistant_gender' => 'required|boolean',
            'assistant.assistant_birthdate' => 'required|date',
            'assistant.assistant_salary' => 'required|numeric',
            'assistant.assistant_experience' => 'required|min:50',
            'assistant.assistant_isactive' => 'required|boolean',
            'assistant.assistant_address.province_id' => 'exists:m_provinces,province_id|nullable',
            'assistant.assistant_address.city_id' => 'exists:m_cities,city_id|nullable',
            'assistant.assistant_address.district_id' => 'exists:m_districts,district_id|nullable',
            'assistant.assistant_address.village_id' => 'exists:m_villages,village_id|nullable',
            'assistant.assistant_address.postalzip_id' => 'exists:m_postalzips,postalzip_id|nullable',
            'assistant.assistant_address.address_street' => 'required|min:20',
            'assistant.assistant_address.address_other' => 'nullable',
            'assistant.assistant_skill.*.skill_name' => 'required|string',
            'assistant.assistant_picture' => 'nullable|mimes:jpeg,jpg,png|min:30|max:1024'
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
            'assistant.assistant_address.province_id' => 'ID Provinsi',
            'assistant.assistant_address.city_id' => 'ID Kota / Kabupaten',
            'assistant.assistant_address.district_id' => 'ID Kecamatan',
            'assistant.assistant_address.village_id' => 'ID Keluharan / Desa',
            'assistant.assistant_address.postalzip_id' => 'ID Kode Pos',
            'assistant.assistant_address.address_street' => 'Alamat Lengkap',
            'assistant.assistant_address.address_other' => 'Alamat Lainnya',
            'assistant.assistant_skill.*.skill_name' => 'Skill',
            'assistant.assistant_picture' => 'Foto Profil'
        ];
    }
}
