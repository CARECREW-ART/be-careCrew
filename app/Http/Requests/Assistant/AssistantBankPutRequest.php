<?php

namespace App\Http\Requests\Assistant;

use Illuminate\Foundation\Http\FormRequest;

class AssistantBankPutRequest extends FormRequest
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
            'assistant_accbank.bank_id' => 'required|exists:m_banks,bank_id',
            'assistant_accbank.accbank_name' => 'required|string',
            'assistant_accbank.accbank_value' => 'required|integer',
        ];
    }

    public function attributes(): array
    {
        return [
            'assistant_accbank.bank_id' => 'Bank',
            'assistant_accbank.accbank_name' => 'Nama Rekening',
            'assistant_accbank.accbank_value' => 'No. Rekening',
        ];
    }
}
