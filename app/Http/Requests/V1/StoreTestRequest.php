<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreTestRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return request()->user()->isAdmin() || request()->user()->hasPermission('examinations-tests-store');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "examination" => ["required", "numeric", "integer", "exists:examinations,id"],
            "title" => ["required", "string", "unique:examination_tests,title,null,id,examination_id," . $this->examination],
            "outof" => ["required", "numeric"],
            "taken_on" => ["required", "date"],
        ];
    }
}
