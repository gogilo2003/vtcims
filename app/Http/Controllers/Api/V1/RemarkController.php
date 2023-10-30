<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\RemarkResource;
use App\Models\Remark;

class RemarkController extends Controller
{
    public function index($id = null)
    {
        $remarks = Remark::all();
        return response()->json(RemarkResource::collection($remarks));
    }

    public function show($id)
    {
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|integer|exists:remarks'
        ]);
        if ($validator->fails()) {
            return $this->validationError($validator);
        }
        $remark = Remark::with('grades')->find($id);
        return response()->json(new RemarkResource($remark));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'remark' => 'required|unique:remarks'
        ]);

        if ($validator->fails()) {
            return $this->validationError($validator);
        }

        $remark = new Remark();
        $remark->remark = $request->remark;
        $remark->save();

        return $this->storeSuccess('Remark stored', ['remark' => new RemarkResource($remark)]);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:remarks,id',
            'remark' => 'required|unique:remarks,remark,' . $request->id,
        ]);

        if ($validator->fails()) {
            return $this->validationError($validator);
        }

        $remark = Remark::find($request->id);
        $remark->remark = $request->remark;
        $remark->save();

        return $this->storeSuccess('Remark updated', ['remark' => new RemarkResource($remark)]);
    }

    public function delete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:remarks'
        ]);
        if ($validator->fails()) {
            return $this->validationError($validator);
        }
        Remark::destroy($request->id);
        return $this->deleteSuccess();
    }
}
