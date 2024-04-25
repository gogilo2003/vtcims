<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCourseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return request()->user()->isAdmin() || request()->user()->hasPermission('courses-update');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'id' => 'required|exists:courses,id',
            'department' => 'required|exists:departments,id',
            'staff' => 'required|exists:staff,id',
            'code' => 'required|unique:courses,code,' . $this->id . '|max:5',
            'name' => 'required|string|unique:courses,name,' . $this->id . ',id,department_id,' . $this->department
        ];
    }
}
