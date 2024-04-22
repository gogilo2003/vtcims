<?php

namespace App\Http\Controllers\V1;

use Inertia\Inertia;
use App\Models\Staff;
use App\Models\Employer;
use App\Models\StaffRole;
use App\Models\Allocation;
use App\Models\StaffStatus;
use Illuminate\Support\Facades\App;
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
                "photo_url" => $member->photo ? Storage::disk('public')->url($member->photo) : asset('img/person_8x10.png'),
                "idno" => $member->idno,
                "gender" => $member->gender,
                "plwd" => $member->plwd,
                "employer" => isset ($member->employer->id) ? [
                    "id" => $member->employer->id,
                    "name" => $member->employer->name
                ] : null,
                "surname" => $member->surname,
                "first_name" => $member->first_name,
                "middle_name" => $member->middle_name,
                "phone" => $member->phone,
                "email" => $member->email,
                "box_no" => $member->box_no,
                "town" => $member->town,
                "teach" => $member->teach,
                "subjects" => $member->subjects,
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

        $statuses = StaffStatus::all()->map(fn(StaffStatus $status) => [
            "id" => $status->id,
            "name" => $status->name,
        ]);

        $employers = Employer::all()->map(fn(Employer $employer) => [
            "id" => $employer->id,
            "name" => $employer->name,
        ]);

        return Inertia::render("Staff/Index", [
            "members" => $members,
            "roles" => $roles,
            "statuses" => $statuses,
            'employers' => $employers,
            "search" => $search
        ]);
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
        $staff->phone = $request->phone;
        $staff->employer_id = $request->employer;
        $staff->staff_role_id = $request->role;
        $staff->staff_status_id = $request->status;
        $staff->gender = $request->gender;
        $staff->teach = $request->teach;
        $staff->plwd = $request->plwd;

        $staff->save();

        return redirect()->back()->with('success', 'Staff member created');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStaffRequest $request, Staff $staff)
    {
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
        $staff->phone = $request->phone;
        $staff->employer_id = $request->employer;
        $staff->staff_role_id = $request->role;
        $staff->staff_status_id = $request->status;
        $staff->gender = $request->gender;
        $staff->teach = $request->teach;
        $staff->plwd = $request->plwd;

        $staff->save();

        return redirect()->back()->with('success', 'Staff member updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Staff $staff)
    {
        $staff->delete();
        return redirect()->back()->with('success', 'Staff member deleted');
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

    public function download($id = null)
    {
        $pdf = App::make('snappy.pdf.wrapper');
        $title = request()->input('tt');
        $status = request()->input('su');
        $role = request()->input('r');
        $teach = request()->input('t');
        $employer = request()->input('e');
        $gender = request()->input('g');
        $plwd = request()->input('p');

        // dd($title, $status, $role, $teach, $employer, $gender, $plwd);

        if ($id) {
            $staff = Staff::find($id);
            $intake_subjects = Allocation::where('staff_id', $id)->with('subject', 'intake')->orderBy('intake_id', 'DESC')->get();
            $pdf->loadView('pdf.staff.view', compact('staff', 'intake_subjects'));
            return $pdf->download(strtoupper('Staff#' . str_pad($staff->id, 4, '0', 0)) . '.pdf');
        } else {
            $staff = Staff::when($status, function ($query) use ($status) {
                $query->where('staff_status_id', $status);
            })
                ->when($role, function ($query) use ($role) {
                    $query->where('staff_role_id', $role);
                })
                ->when($employer, function ($query) use ($employer) {
                    $query->where('employer_id', $employer);
                })
                ->when($teach, function ($query) use ($teach) {
                    $query->where('teach', $teach);
                })
                ->when($gender, function ($query) use ($gender) {
                    $query->where('gender', $gender);
                })
                ->when($plwd, function ($query) use ($plwd) {
                    $query->where('plwd', $plwd);
                })
                ->get();

            $pdf->loadView('pdf.staff.list', compact('staff'))
                ->setOrientation('landscape')
                ->setPaper('A4')
                ->setOption('footer-center', 'Page [page] of [toPage]')
                ->setOption('footer-font-size', 8);
            return $pdf->stream(strtoupper('STAFF_LIST_' . date('d-M-Y')) . '.pdf');
        }
    }
}
