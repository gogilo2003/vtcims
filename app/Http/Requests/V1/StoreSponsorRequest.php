<?php

namespace App\Http\Requests\V1;

use App\Rules\PhoneNumber;
use App\Support\PhoneTrait;
use Illuminate\Foundation\Http\FormRequest;

class StoreSponsorRequest extends FormRequest
{
    use PhoneTrait;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return request()->user()->isAdmin() || request()->user()->hasPermission('sponsors-store');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|unique:sponsors,name',
            'contact_person' => 'required',
            'email' => 'nullable|email',
            'phone' => ['nullable', new PhoneNumber()],
        ];
    }
}