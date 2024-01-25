<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class UpdateIntakeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return request()->user()->isAdmin() || request()->user()->hasPermission('intakes-update');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'id' => 'required|integer|exists:intakes',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:' . $this->start_date,
            'instructor' => 'required|integer|exists:staff,id',
            'course' => 'required|integer|exists:courses,id',
            'name' => 'required|string|unique:intakes,name,' . $this->id . ',id',
        ];
    }
}
