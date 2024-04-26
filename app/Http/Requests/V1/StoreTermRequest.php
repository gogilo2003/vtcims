<?php

namespace App\Http\Requests\V1;

use Illuminate\Http\Response;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;


class StoreTermRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return request()->user()->isAdmin() || request()->user()->hasPermission('terms-store');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "name" => ["required", "string", "unique:terms,name,null,id,year," . $this->year],
            "year" => ["required", "numeric", "integer"],
            "start_at" => ["required", "date"],
            "end_at" => ["required", "date"],
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        // return redirect()->back()->withErrors($validator);
    }
}
