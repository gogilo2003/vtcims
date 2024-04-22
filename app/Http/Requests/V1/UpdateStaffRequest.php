<?php

namespace App\Http\Requests\V1;

use App\Rules\PhoneNumber;
use App\Support\PhoneTrait;
use Illuminate\Foundation\Http\FormRequest;

class UpdateStaffRequest extends FormRequest
{
    use PhoneTrait;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return request()->user()->isAdmin() || request()->user()->hasPermission('staff-members-update');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'id' => ['required', 'numeric', 'integer', 'exists:staff,id'],
            'idno' => 'required|numeric|unique:staff,phone,' . $this->id . ',id',
            'pfno' => 'nullable|alpha_num|unique:staff,pfno,' . $this->id . ',id',
            'manno' => 'nullable|alpha_num|unique:staff,manno,' . $this->id . ',id',
            'phone' => ['nullable', 'string', new PhoneNumber(), 'unique:staff,phone,' . $this->id . ',id'],
            'email' => 'nullable|string|email|unique:staff,email,' . $this->id . ',id',
            'photo' => 'nullable|file|image',
            'role' => 'required|numeric|integer|exists:staff_roles,id',
            'job_group' => 'required|numeric|integer|exists:job_groups,id',
            'designation' => 'required|numeric|integer|exists:designations,id',
        ];
    }

}
