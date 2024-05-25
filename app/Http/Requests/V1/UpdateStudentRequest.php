<?php

namespace App\Http\Requests\V1;

use App\Rules\PhoneNumber;
use App\Support\StudentPrepareTrait;
use Illuminate\Foundation\Http\FormRequest;

class UpdateStudentRequest extends FormRequest
{
    use StudentPrepareTrait;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return request()->user()->isAdmin() || request()->user()->hasPermission('students-update');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'id' => 'required|integer|exists:students',
            'surname' => 'required',
            'first_name' => 'required',
            'email' => 'nullable|email',
            'date_of_birth' => 'required|date',
            'birth_certificate_no' => 'nullable|string|unique:students,birth_cert_no',
            'idno' => 'nullable|numeric|unique:students,idno,' . $this->id,
            'gender' => 'required',
            'date_of_admission' => 'required|date',
            'intake' => 'required|numeric|integer|exists:intakes,id',
            'program' => 'required|numeric|integer|exists:programs,id',
            'sponsor' => 'required|numeric|integer|exists:sponsors,id',
            'role' => 'required|numeric|integer|exists:student_roles,id',
            'status' => 'required',
            'guardian_name' => 'nullable|string',
            'guardian_phone' => ['nullable', 'string', new PhoneNumber()],
            'guardian_email' => 'nullable|string|email',
            'phone' => ['nullable', 'string', new PhoneNumber()],
        ];
    }

}
