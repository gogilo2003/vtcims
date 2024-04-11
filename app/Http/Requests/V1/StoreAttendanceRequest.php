<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreAttendanceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return request()->user()->isAdmin() || request()->user()->hasPermission('attendance-mark');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "allocation" => "required|integer|exists:staff_subject,id",
            "mark_at" => "required|date",
            "students" => "nullable|array|min:1",
            "students.*" => "required|integer|exists:students,id",
        ];
    }

    function messages(): array
    {
        return [
            "students.min" => "You must Select at least one student"
        ];
    }
}
