<?php

namespace App\Http\Controllers\V1;

use Inertia\Inertia;
use App\Models\BogMember;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBogMemberRequest;
use App\Http\Requests\UpdateBogMemberRequest;

class BogMemberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render("Bog/Index");
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

        $member->name = $request->name;
        $member->id_number = $request->id_number;

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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BogMember $bogMember)
    {
        //
    }
}
