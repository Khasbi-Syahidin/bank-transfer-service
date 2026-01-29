<?php

namespace App\Http\Requests;

use App\Domain\Transfer\Services\BankClientRegistry;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TransferRequest extends FormRequest
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
        $bankCodes = app(BankClientRegistry::class)->getBankCodes();

        return [
            'transfer_id' => 'required|unique:transfer_logs,transfer_id',
            'source_bank_code' => ['required', Rule::in($bankCodes)],
            'source_account' => 'required',
            'amount' => 'required|numeric|gt:0',
            'currency' => 'required|in:IDR,USD',
            'destination_bank_code' => ['required', Rule::in($bankCodes)],
            'destination_account' => 'required',
            'description' => 'nullable',
            'transfer_time' => 'nullable',
        ];
    }
}
