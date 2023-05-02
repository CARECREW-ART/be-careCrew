<?php

namespace App\Http\Requests\Assistant;

use Illuminate\Foundation\Http\FormRequest;

class AssistantPutRequest extends FormRequest
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
            "assistant.assistant_fullname" => "required|string",
            "assistant.assistant_nickname" => "required|string|max:50",
            "assistant.assistant_telp" => "required|max:16",
            "assistant.assistant_gender" => "required|integer",
            "assistant.assistant_birthdate" => [
                'required',
                'date',
                'date_format:Y-m-d',
                'before_or_equal:' . date("Y-m-d", strtotime("-18 year")),
                'after_or_equal:' . date("Y-m-d", strtotime("-55 year"))
            ],
            "assistant.assistant_salary" => "required|numeric",
            "assistant.assistant_experience" => "required|min:50",
            "assistant.assistant_skills" => "required|string",
            "password" => "required",
        ];
    }

    public function attributes(): array
    {
        return [
            'assistant.assistant_fullname' => 'Nama Lengkap',
            'assistant.assistant_nickname' => 'Nama Panggilan',
            'assistant.assistant_telp' => 'Nomor Telepon',
            'assistant.assistant_gender' => 'Jenis Kelamin',
            'assistant.assistant_birthdate' => 'Tanggal Lahir',
            'assistant.assistant_salary' => 'Gaji',
            'assistant.assistant_experience' => 'Pengalaman',
            'assistant.assistant_skills' => 'Skill',
        ];
    }
}
