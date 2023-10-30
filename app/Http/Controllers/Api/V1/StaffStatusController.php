<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use Ogilo\ApiResponseHelpers;
use App\Http\Controllers\Controller;
use App\Models\StaffStatus;
use App\Http\Resources\StaffStatusResource;

class StaffStatusController extends Controller
{
    use ApiResponseHelpers;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return StaffStatusResource::collection(StaffStatus::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:staff_statuses'
        ]);

        if ($validator->fails()) {
            return $this->validationError();
        }

        $status = new StaffStatus();
        $status->name = $request->name;
        $status->description = $request->description;
        $status->save();

        return $this->storeSuccess('Status saved', ['status' => $status]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\StaffStatus  $staffStatus
     * @return \Illuminate\Http\Response
     */
    public function show(StaffStatus $staffStatus)
    {
        // $validator = Validator::make(['id'=>$id], [
        //     'id' => 'required|exists:staff_statuses',
        // ]);

        // if ($validator->fails()) {
        //     return $this->validationError();
        // }

        return new StaffStatusResource($staffStatus);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\StaffStatus  $staffStatus
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StaffStatus $staffStatus)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:staff_statuses',
            'name' => 'required|unique:staff_statuses'
        ]);

        if ($validator->fails()) {
            return $this->validationError();
        }

        $status = StaffStatus::find($request->id);
        $status->name = $request->name;
        $status->description = $request->description;
        $status->save();

        return $this->storeSuccess('Status updated', ['status' => $status]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\StaffStatus  $staffStatus
     * @return \Illuminate\Http\Response
     */
    public function destroy(StaffStatus $staffStatus)
    {
        //
    }
}
