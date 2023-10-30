<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\GradeResource;
use App\Models\Grade;

class GradeController extends Controller
{
    public function index($id = null)
    {
        $grades = Grade::all();
        return response()->json(GradeResource::collection($grades));
    }

    public function show($id)
    {
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|integer|exists:grades'
        ]);
        if ($validator->fails()) {
            return $this->validationError($validator);
        }
        $grade = Grade::with('remark')->find($id);
        return response()->json(new GradeResource($grade));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'grade' => 'required|unique:grades',
            'min_score' => 'required',
            'max_score' => 'required',
            'remark' => 'required|integer|exists:remarks,id',
        ]);

        if ($validator->fails()) {
            return $this->validationError($validator);
        }

        $grade = new Grade();
        $grade->grade = $request->grade;
        $grade->min_score = $request->min_score;
        $grade->max_score = $request->max_score;
        $grade->remark_id = $request->remark;
        $grade->save();

        return $this->storeSuccess('Grade stored', ['grade' => new GradeResource($grade)]);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:grades,id',
            'grade' => 'required|unique:grades,grade,' . $request->id,
            'min_score' => 'required',
            'max_score' => 'required',
            'remark' => 'required|integer|exists:remarks,id',
        ]);

        if ($validator->fails()) {
            return $this->validationError($validator);
        }

        $grade = Grade::find($request->id);
        $grade->grade = $request->grade;
        $grade->min_score = $request->min_score;
        $grade->max_score = $request->max_score;
        $grade->remark_id = $request->remark;
        $grade->save();

        return $this->storeSuccess('Grade updated', ['grade' => new GradeResource($grade)]);
    }

    public function delete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:grades'
        ]);
        if ($validator->fails()) {
            return $this->validationError($validator);
        }
        Grade::destroy($request->id);
        return $this->deleteSuccess();
    }
}
