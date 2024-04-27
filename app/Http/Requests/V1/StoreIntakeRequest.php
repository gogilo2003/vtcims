<?php

namespace App\Http\Requests\V1;

use Illuminate\Support\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class StoreIntakeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return request()->user()->isAdmin() || request()->user()->hasPermission('intake-store');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:' . $this->start_date,
            'instructor' => 'required|integer|exists:staff,id',
            'course' => 'required|integer|exists:courses,id',
            'name' => 'required|string|unique:intakes,name',
        ];
    }

    /**
     * Get the messages for the various rules
     *
     * @return array
     */
    function messages(): array
    {
        $start = Carbon::parse($this->start_date)->isoFormat('ddd, D MM, Y');
        // dd($start);
        return [
            'end_date.after' => 'The :attribute field must be a date after ' . $start
        ];
    }
}
