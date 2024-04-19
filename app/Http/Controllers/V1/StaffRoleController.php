<?php

namespace App\Http\Controllers\V1;

use App\Models\StaffRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreStaffRoleRequest;
use App\Http\Requests\V1\UpdateStaffRoleRequest;
use Inertia\Inertia;

class StaffRoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $search = request()->input("search");
        $roles = StaffRole::when($search, function ($query) use ($search) {
            $query->where("name", "like", "%" . $search . "%");
        })->paginate(8);
        return Inertia::render("Staff/StaffRoles", ['roles' => $roles, 'search' => $search]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStaffRoleRequest $request)
    {
        $role = new StaffRole();
        $role->name = $request->name;
        $role->save();

        return redirect()->back()->with('success', 'Staff role created');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStaffRoleRequest $request, StaffRole $staffRole)
    {
        $staffRole->name = $request->name;
        $staffRole->save();

        return redirect()->back()->with('success', 'Staff role updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StaffRole $staffRole)
    {
        $staffRole->delete();

        return redirect()->back()->with('success', 'Staff role deleted');
    }
}
