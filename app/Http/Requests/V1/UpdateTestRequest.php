<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTestRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return request()->user()->isAdmin() || request()->user()->hasPermission('examinations-tests-update');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "id" => ["required", "numeric", "integer", "exists:examination_tests,id"],
            "examination" => ["required", "numeric", "integer", "exists:examinations,id"],
            "title" => ["required", "string", "unique:examination_tests,title," . $this->id . ",id,examination_id," . $this->examination],
            "outof" => ["required", "numeric"],
            "taken_on" => ["required", "date"],
        ];
    }
}
