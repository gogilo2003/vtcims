<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return request()->user()->hasPermission();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'surname' => 'required',
            'first_name' => 'required',
            'email' => 'nullable|email',
            'date_of_birth' => 'required|date',
            'birth_certificate_no' => 'nullable|unique:students,birth_cert_no',
            'idno' => 'nullable|unique:students,idno',
            'gender' => 'required',
            'date_of_admission' => 'required|date',
            'intake' => 'required|exists:intakes,id',
            'program' => 'required|exists:programs,id',
            'sponsor' => 'required|exists:sponsors,id',
            'student_role' => 'required|exists:student_roles,id',
            'status' => 'required',
        ];
    }
}
