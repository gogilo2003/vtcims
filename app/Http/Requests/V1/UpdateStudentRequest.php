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
            'birth_certificate_no' => 'nullable|unique:students,birth_cert_no',
            'idno' => 'nullable|unique:students,idno,' . $this->id,
            'gender' => 'required',
            'date_of_admission' => 'required|date',
            'intake' => 'required|exists:intakes,id',
            'program' => 'required|exists:programs,id',
            'sponsor' => 'required|exists:sponsors,id',
            'role' => 'required|exists:student_roles,id',
            'status' => 'required',
            'phone' => ['nullable', 'string', new PhoneNumber()],
        ];
    }

}
