<?php

namespace App\Http\Requests\V1;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateFeeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return request()->user()->isAdmin() || request()->user()->hasPermission('accounts-fees-update');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'id' => 'required|integer|exists:fees,id',
            'term' => [
                'required',
                'exists:terms,id',
                Rule::unique('fees', 'term_id')->where(
                    function ($query) {
                        return $query->where('course_id', $this->course);
                    }
                )->ignore($this->id)
            ],
            'course' => 'required|exists:courses,id',
            'amount' => 'required|numeric',
        ];
    }

    function messages(): array
    {
        return [
            'term.unique' => 'You can\'t change this fee since another fee for the same term and course already exists'
        ];
    }
}
