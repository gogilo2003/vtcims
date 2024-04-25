<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreCourseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return request()->user()->isAdmin() || request()->user()->hasPermission('courses-store');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'department' => 'required|numeric|integer|exists:departments,id',
            'staff' => 'required|numeric|integer|exists:staff,id',
            'code' => 'required|string|unique:courses|max:5',
            'name' => 'required|string|unique:courses,name,null,id,department_id,' . $this->department
        ];
    }
}
