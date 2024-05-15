<?php

namespace App\Http\Requests\V1;

use App\Support\Util;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRoleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return request()->user()->isAdmin() || request()->user()->hasPermission('admin-roles-update');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // dd($this->all());
        return [
            "id" => ["required", "numeric", "integer", "exists:roles,id"],
            "name" => ["required", "string", "unique:roles,name," . $this->id . ",id"],
            "permissions" => ["required", "array", "min:1"],
            "permissions.*" => ["string", Rule::in(Util::getRoutes()->pluck('name')->toArray())]
        ];
    }
}
