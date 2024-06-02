<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreAllocationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return request()->user()->isAdmin() || request()->user()->hasPermission('allocations-store');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "term" => "required|integer|exists:terms,id",
            "instructor" => "required|integer|exists:staff,id",
            "subject" => "required|integer|exists:subjects,id",
            "intakes" => "required|array|min:1",
            "intakes.*" => "required|integer|exists:intakes,id",
        ];
    }
}
