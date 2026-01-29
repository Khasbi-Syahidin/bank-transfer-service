<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransferExecuteRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'transfer_id' => 'required|string',
            'source_bank_code' => 'required|string',
            'destination_bank_code' => 'required|string',
            'amount' => 'required|numeric|min:0.01',
            'currency' => 'required|in:IDR,USD',
        ];
    }
}
