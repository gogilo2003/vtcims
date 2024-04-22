<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class UpdateJobGroupRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return request()->user()->isAdmin() || request()->user()->hasPermission('staff-job_groups-update');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "id" => ["required", "numeric", "integer", "exists:job_groups,id"],
            "name" => ["required", "string", "unique:job_groups,name," . $this->id . ",id"]
        ];
    }
}
