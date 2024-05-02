<?php

namespace App\Http\Requests\V1;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreFeeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return request()->user()->isAdmin() || request()->user()->hasPermission('accounts-fees-store');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'term' => [
                'required',
                'exists:terms,id',
                Rule::unique('fees', 'term_id')->where(function ($query) {
                    return $query->where('course_id', $this->course);
                })
            ],
            'course' => 'required|exists:courses,id',
            'amount' => 'required|numeric',
        ];
    }

    public function messages(): array
    {
        return [
            'term.unique' => 'The fee you tried to create already exists'
        ];
    }
}
