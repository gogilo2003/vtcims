<?php

namespace App\Http\Controllers\V1;

use App\Models\BogPosition;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreBogPositionRequest;
use App\Http\Requests\V1\UpdateBogPositionRequest;
use Inertia\Inertia;

class BogPositionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $search = request()->input("search");

        $positions = BogPosition::with('members')->when($search, function ($query) use ($search) {
            $query->where('name', 'LIKE', '%' . $search . '%');
        })->paginate()->through(fn($position) => [
                "id" => $position->id,
                "name" => $position->name,
                "members" => $position->members->count(),
            ]);

        return Inertia::render("Bog/BogPositions", ["positions" => $positions]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBogPositionRequest $request)
    {
        $position = new BogPosition();
        $position->name = $request->input("name");
        $position->save();

        return redirect()->back()->with('success', 'Position Created');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBogPositionRequest $request, BogPosition $bogPosition)
    {
        $bogPosition->name = $request->input("name");
        $bogPosition->save();

        return redirect()->back()->with('success', 'Position updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BogPosition $bogPosition)
    {
        $bogPosition->delete();
        return redirect()->back()->with('success', 'BOG Position Deleted');
    }
}
