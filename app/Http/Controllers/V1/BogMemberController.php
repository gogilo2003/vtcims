<?php

namespace App\Http\Controllers\V1;

use Inertia\Inertia;
use App\Models\BogMember;
use App\Models\BogPosition;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\V1\StoreBogMemberRequest;
use App\Http\Requests\V1\UpdateBogMemberRequest;

class BogMemberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $search = request()->input("search");

        $members = BogMember::when($search, function ($query) use ($search) {
            $query->where('surname', 'LIKE', '%' . $search . '%')
                ->orWhere('first_name', '%' . $search . '%')
                ->orWhere('middle_name', '%' . $search . '%');

            $ar = explode(' ', $search);
            if (count($ar) > 1) {
                $query->orWhere(function ($query) use ($ar) {
                    if (isset ($ar[0])) {
                        $query->where('surname', 'LIKE', $ar[0] . '%');
                    }
                    if (isset ($ar[1])) {
                        $query->where('first_name', 'LIKE', $ar[1] . '%');
                    }
                    if (isset ($ar[2])) {
                        $query->where('middle_name', 'LIKE', $ar[2] . '%');
                    }
                });
            }
        })->paginate()->through(fn($member) => [
                "id" => $member->id,
                "photo" => $member->photo,
                "photo_url" => Storage::disk('public')->url($member->photo),
                "idno" => $member->idno,
                "gender" => $member->gender,
                "plwd" => $member->plwd ? 'Yes' : 'No',
                "surname" => $member->surname,
                "first_name" => $member->first_name,
                "middle_name" => $member->middle_name,
                "phone" => $member->phone,
                "email" => $member->email,
                "box_no" => $member->box_no,
                "town" => $member->town,
                "position" => [
                    "id" => $member->position->id,
                    "name" => $member->position->name,
                ],
                "active" => $member->active,
                "term_start_at" => $member->term_start_at,
                "term_end_at" => $member->term_end_at,
                "term_count" => $member->term_count,
            ]);

        $positions = BogPosition::all()->map(fn($position) => [
            "id" => $position->id,
            "name" => $position->name,
        ]);

        return Inertia::render("Bog/Index", ["members" => $members, "positions" => $positions]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBogMemberRequest $request)
    {
        $member = new BogMember();

        $member->idno = $request->idno;
        $member->gender = $request->gender;
        $member->plwd = $request->plwd;
        $member->surname = $request->surname;
        $member->first_name = $request->first_name;
        $member->middle_name = $request->middle_name;
        $member->phone = $request->phone;
        $member->email = $request->email;
        $member->box_no = $request->box_no;
        $member->post_code = $request->post_code;
        $member->town = $request->town;
        $member->position_id = $request->position;
        $member->active = $request->active;
        $member->term_start_at = $request->term_start_at;
        $member->term_end_at = $request->term_end_at;
        $member->term_count = $request->term_count;

        $member->save();

        return redirect()->back()->with("success", "Member created");
    }

    /**
     * Display the specified resource.
     */
    public function show(BogMember $bogMember)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BogMember $bogMember)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBogMemberRequest $request, BogMember $bogMember)
    {
        $bogMember->idno = $request->idno;
        $bogMember->gender = $request->gender;
        $bogMember->plwd = $request->plwd;
        $bogMember->surname = $request->surname;
        $bogMember->first_name = $request->first_name;
        $bogMember->middle_name = $request->middle_name;
        $bogMember->phone = $request->phone;
        $bogMember->email = $request->email;
        $bogMember->box_no = $request->box_no;
        $bogMember->post_code = $request->post_code;
        $bogMember->town = $request->town;
        $bogMember->position_id = $request->position;
        $bogMember->active = $request->active;
        $bogMember->term_start_at = $request->term_start_at;
        $bogMember->term_end_at = $request->term_end_at;
        $bogMember->term_count = $request->term_count;

        $bogMember->save();

        return redirect()->back()->with("success", "Member updated");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BogMember $bogMember)
    {
        //
    }
}
