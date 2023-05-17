<?php

namespace App\Http\Requests\Assistant;

use Illuminate\Foundation\Http\FormRequest;

class AssistantPicturePutRequest extends FormRequest
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
            'assistant_picture' => 'required|mimes:jpeg,jpg,png|min:30|max:10240',
        ];
    }

    public function attributes(): array
    {
        return [
            'assistant_picture' => 'Foto Profile',
        ];
    }
}
