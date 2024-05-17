<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreFeeTransactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return request()->user()->isAdmin() || request()->user()->hasPermission('accounts-fees-transactions-store');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "amount" => ["required", "numeric", "min:1"],
            "fee" => ["required", "numeric", "exists:fees,id"],
            "mode" => ["required", "numeric", "exists:fee_transaction_modes,id"],
            "student" => ["required", "numeric", "exists:students,id"],
            "type" => ["required", "string", "exists:fee_transaction_types,code"],
        ];
    }
}
