<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;

class CustomerPicturePutRequest extends FormRequest
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
            'customer_picture' => 'required|mimes:jpeg,jpg,png|min:30|max:10240',
        ];
    }

    public function attributes(): array
    {
        return [
            'customer_picture' => 'Foto Profile',
        ];
    }
}
