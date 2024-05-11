<?php

namespace App\Http\Requests\V1;

use App\Rules\PhoneNumber;
use App\Support\PhoneTrait;
use App\Support\Util;
use Illuminate\Foundation\Http\FormRequest;

class StoreStaffRequest extends FormRequest
{

    use PhoneTrait;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return request()->user()->isAdmin() || request()->user()->hasPermission('staff-members-store');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'idno' => 'required|numeric|unique:staff',
            'pfno' => 'nullable|alpha_num|unique:staff',
            'manno' => 'nullable|alpha_num|unique:staff',
            'phone' => ['nullable', 'string', new PhoneNumber(), 'unique:staff'],
            'email' => 'nullable|string|email|unique:staff',
            'photo' => 'nullable|file|image',
            'role' => 'required|numeric|integer|exists:staff_roles,id',
        ];
    }

    function prepareForValidation(): void
    {
        $this->merge(['phone' => Util::formatPhoneNumber($this->phone)]);
    }
}
