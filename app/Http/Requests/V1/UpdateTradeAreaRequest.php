<?php

namespace App\Http\Requests\V1;

use App\Models\TradeArea;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTradeAreaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return request()->user()->isAdmin() || request()->user()->hasPermission('trade-areas-update');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'id' => 'required|exists:trade_areas,id',
            'name' => ['required', 'string', 'max:255', Rule::unique(TradeArea::class)->ignore($this->id)],
            'description' => ['nullable', 'string'],
        ];
    }
}
