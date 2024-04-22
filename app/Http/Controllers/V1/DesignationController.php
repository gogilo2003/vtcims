<?php

namespace App\Http\Controllers\V1;

use Inertia\Inertia;
use App\Models\Designation;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreDesignationRequest;
use App\Http\Requests\V1\UpdateDesignationRequest;

class DesignationController extends Controller
{
    public function index()
    {
        $search = request()->input('search');

        $designations = Designation::when($search, function ($query) use ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        })->paginate(8);

        return Inertia::render('Staff/Designations', ['designations' => $designations]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDesignationRequest $request)
    {
        $designation = new Designation();

        $designation->name = $request->name;
        $designation->save();

        return redirect()->back()->with('success', 'Designation Created');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDesignationRequest $request, Designation $designation)
    {
        $designation->name = $request->name;
        $designation->save();

        return redirect()->back()->with('success', 'Designation Updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Designation $designation)
    {
        $designation->delete();

        return redirect()->back()->with('success', 'Designation deleted');
    }
}
