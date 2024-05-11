<?php

namespace App\Http\Requests\V1;

use App\Rules\PhoneNumber;
use App\Support\Util;
use Illuminate\Foundation\Http\FormRequest;

class StoreBogMemberRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return request()->user()->isAdmin() || request()->user()->hasPermission('bog-members-store');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "idno" => "nullable|numeric|integer",
            "gender" => "nullable|string",
            "plwd" => "nullable|numeric|integer",
            "surname" => "required|string",
            "first_name" => "required|string",
            "middle_name" => "nullable|string",
            "phone" => ["nullable", "string", new PhoneNumber()],
            "email" => "nullable|string|email",
            "box_no" => "nullable|string",
            "post_code" => "nullable|string",
            "town" => "nullable|string",
            "position" => "required|numeric|integer|exists:bog_positions,id",
            "active" => "required|numeric|integer|max:1",
            "term_start_at" => "nullable|date",
            "term_end_at" => "nullable|date",
            "term_count" => "nullable|numeric|integer",
        ];
    }

    function prepareForValidation()
    {
        $this->merge(['phone' => Util::formatPhoneNumber($this->phone)]);
    }
}
