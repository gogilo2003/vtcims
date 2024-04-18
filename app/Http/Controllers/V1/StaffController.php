<?php

namespace App\Http\Controllers\V1;

use Inertia\Inertia;
use App\Models\Staff;
use App\Models\StaffRole;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\V1\StoreStaffRequest;
use App\Http\Requests\V1\UpdateStaffRequest;
use App\Http\Requests\V1\UploadStaffMemberPictureRequest;

class StaffController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $search = request()->input("search");

        $members = Staff::with('status', 'role')
            ->when($search, function ($query) use ($search) {
                $names = explode(" ", $search);
                foreach ($names as $name) {
                    $query->where(function ($query) use ($name) {
                        $query->where('surname', 'like', '%' . $name . '%')
                            ->orWhere('first_name', 'like', '%' . $name . '%')
                            ->orWhere('middle_name', 'like', '%' . $name . '%');
                    });
                }
            })->paginate(7)->through(fn($member) => [
                "id" => $member->id,
                "photo" => $member->photo,
                "photo_url" => Storage::disk('public')->url($member->photo),
                "idno" => $member->idno,
                "gender" => $member->gender,
                "plwd" => $member->plwd,
                "employer" => $member->employer,
                "surname" => $member->surname,
                "first_name" => $member->first_name,
                "middle_name" => $member->middle_name,
                "phone" => $member->phone,
                "email" => $member->email,
                "box_no" => $member->box_no,
                "town" => $member->town,
                "teach" => $member->teach,
                "role" => [
                    "id" => $member->role->id,
                    "name" => $member->role->name,
                ],
                "status" => [
                    "id" => $member->status->id,
                    "name" => $member->status->name,
                ],
            ]);

        $roles = StaffRole::all()->map(fn($role) => [
            "id" => $role->id,
            "name" => $role->name,
        ]);

        return Inertia::render("Staff/Index", ["members" => $members, "roles" => $roles, "search" => $search]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStaffRequest $request)
    {
        $staff = new Staff;

        $staff->idno = $request->idno;
        $staff->pfno = $request->pfno ? $request->pfno : null;
        $staff->manno = $request->manno ? $request->manno : null;
        $staff->surname = $request->surname;
        $staff->first_name = $request->first_name;
        $staff->middle_name = $request->middle_name;
        $staff->box_no = $request->box_no;
        $staff->post_code = $request->post_code;
        $staff->town = $request->town;
        $staff->email = $request->email;
        $staff->phone = clean_isdn($request->phone);
        $staff->employer = $request->employer;
        $staff->staff_role_id = $request->staff_role_id;
        $staff->gender = $request->gender;
    }

    /**
     * Display the specified resource.
     */
    public function show(Staff $staff)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Staff $staff)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStaffRequest $request, Staff $staff)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Staff $staff)
    {
        //
    }
    function picture(UploadStaffMemberPictureRequest $request, Staff $staff)
    {
        if ($request->hasFile('photo')) {
            $file = $request->photo;

            if ($file->isValid()) {
                if ($staff->photo) {
                    if (Storage::disk('public')->exists($staff->photo)) {
                        Storage::disk('public')->delete($staff->photo);
                    }
                }

                $staff->photo = $file->storePublicly('staff_members', ["disk" => "public"]);

                $staff->save();

                return redirect()
                    ->back()
                    ->with('success', 'Picture uploaded');
            }
            return redirect()->back()->with('error', 'An invalid picture file detected');
        }
        return redirect()->back()->with('error', 'No File has been uploaded');
    }
}
