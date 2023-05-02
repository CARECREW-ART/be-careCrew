<?php

namespace App\Http\Requests\Assistant;

use Illuminate\Foundation\Http\FormRequest;

class AssistantFavoritePostRequest extends FormRequest
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
            "assistant_username" => "required|string|exists:m_assistants,assistant_username|unique:assistant_favorites,assistant_id"
        ];
    }

    public function attributes(): array
    {
        return [
            "assistant_username" => "Username"
        ];
    }
}
