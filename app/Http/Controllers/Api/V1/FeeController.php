<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Models\Fee;
use Illuminate\Validation\Rule;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\FeeResource;

class FeeController extends Controller
{
    public function index($id = null)
    {
        $fees = Fee::orderBy('term_id', 'DESC')->orderBy('created_at', 'DESC')->get();
        return response()->json(FeeResource::collection($fees));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'term' => ['required', 'exists:terms,id', Rule::unique('fees', 'term_id')->where(function ($query) use ($request) {
                return $query->where('course_id', $request->course);
            })],
            'course' => 'required|exists:courses,id',
            'amount' => 'required|numeric',
        ], [
            'term.unique' => 'The fee you tried to create already exists'
        ]);

        if ($validator->fails()) {
            return $this->validationError($validator);
        }

        $fee = new Fee();
        $fee->term_id = $request->term;
        $fee->course_id = $request->course;
        $fee->amount = $request->amount;
        $fee->save();

        return $this->storeSuccess('Fee stored', ['fee' => new FeeResource($fee)]);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:fees,id',
            'term' => [
                'required',
                'exists:terms,id',
                Rule::unique('fees', 'term_id')->where(
                    function ($query) use ($request) {
                        return $query->where('course_id', $request->course);
                    }
                )->ignore($request->id)
            ],
            'course' => 'required|exists:courses,id',
            'amount' => 'required|numeric',
        ], [
            'term.unique' => 'You can\'t update this fee since another fee for the same term and course already exists'
        ]);

        if ($validator->fails()) {
            return $this->validationError($validator);
        }

        $fee = Fee::find($request->id);
        $fee->term_id = $request->term;
        $fee->course_id = $request->course;
        $fee->amount = $request->amount;
        $fee->save();

        return $this->storeSuccess('Fee updated', ['fee' => new FeeResource($fee)]);
    }
}
