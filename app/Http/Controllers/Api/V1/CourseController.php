<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Models\Course;
use Illuminate\Validation\Rule;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\CourseResource;

class CourseController extends Controller
{
    public function index($id = null)
    {
        $fees = Course::all();
        return response()->json(CourseResource::collection($fees));
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

        $fee = new Course();
        $fee->term_id = $request->term;
        $fee->course_id = $request->course;
        $fee->amount = $request->amount;
        $fee->save();

        return $this->storeSuccess('Course stored', ['fee' => new CourseResource($fee)]);
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

        $fee = Course::find($request->id);
        $fee->term_id = $request->term;
        $fee->course_id = $request->course;
        $fee->amount = $request->amount;
        $fee->save();

        return $this->storeSuccess('Course updated', ['fee' => new CourseResource($fee)]);
    }
}
