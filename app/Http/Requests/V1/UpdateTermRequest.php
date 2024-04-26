<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTermRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return request()->user()->isAdmin() || request()->user()->hasPermission('terms-update');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "id" => ["required", "numeric", "integer", "exists:terms,id"],
            "name" => ["required", "string", "unique:terms,name," . $this->id . ",id,year," . $this->year],
            "year" => ["required", "numeric", "integer"],
            "start_at" => ["required", "date"],
            "end_at" => ["required", "date"],
        ];
    }
}
