<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UserPasswordPutRequest extends FormRequest
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
            'old_password' => 'required',
            'new_password' => 'required|min:8|confirmed|regex:/^.*(?=.*?[A-Z])(?=.*[a-z])(?=.*[0-9]).*$/',
        ];
    }

    public function attributes(): array
    {
        return [
            'old_password' => 'Kata Sandi Lama',
            'new_password' => 'Kata Sandi Baru',
        ];
    }
}
