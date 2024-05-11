<?php

namespace App\Http\Requests\V1;

use App\Support\Util;
use App\Rules\PhoneNumber;
use App\Support\PhoneTrait;
use Illuminate\Foundation\Http\FormRequest;

class UpdateBogMemberRequest extends FormRequest
{
    use PhoneTrait;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return request()->user()->isAdmin() || request()->user()->hasPermission('bog-members-update');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "id" => "required|numeric|integer|exists:bog_members,id",
            "idno" => "nullable|numeric|integer|unique:bog_members,idno," . $this->id . ",id",
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
            "position" => "nullable|numeric|integer|exists:bog_positions,id",
            "active" => "nullable|numeric|integer|max:1",
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
