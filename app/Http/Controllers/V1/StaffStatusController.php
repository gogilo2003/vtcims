<?php

namespace App\Http\Controllers\V1;

use Inertia\Inertia;
use App\Models\StaffStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreStaffStatusRequest;
use App\Http\Requests\V1\UpdateStaffStatusRequest;

class StaffStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $search = request()->input("search");
        $statuses = StaffStatus::when($search, function ($query) use ($search) {
            $query->where("name", "like", "%" . $search . "%");
        })->paginate(8);
        return Inertia::render("Staff/StaffStatuses", ['statuses' => $statuses, 'search' => $search]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStaffStatusRequest $request)
    {
        $status = new StaffStatus();
        $status->name = $request->input('name');
        $status->save();

        return redirect()->back()->with('success', 'Staff Status created');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStaffStatusRequest $request, StaffStatus $staffStatus)
    {
        $staffStatus->name = $request->input('name');
        $staffStatus->save();

        return redirect()->back()->with('success', 'Staff Status updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StaffStatus $staffStatus)
    {
        $staffStatus->delete();

        return redirect()->back()->with('success', 'Staff Status deleted');
    }
}
