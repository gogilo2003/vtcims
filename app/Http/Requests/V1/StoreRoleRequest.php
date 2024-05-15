<?php

namespace App\Http\Requests\V1;

use App\Support\Util;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreRoleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return request()->user()->isAdmin() || request()->user()->hasPermission('admin-roles-store');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "name" => ["required", "string", "unique:roles,name"],
            "permissions" => ["required", "array", "min:1"],
            "permissions.*" => ["string", Rule::in(Util::getRoutes())]
        ];
    }
}
