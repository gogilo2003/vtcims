<?php

namespace App\Http\Controllers\V1;

use Inertia\Inertia;
use App\Models\Program;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreProgramRequest;
use App\Http\Requests\V1\UpdateProgramRequest;

class ProgramController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $search = request()->input('search');

        $programs = Program::when($search, function ($query) use ($search) {
            $query->where('name', 'LIKE', '%' . $search . '%');
        })->paginate(10)->through(fn ($item) => [
            "id" => $item->id,
            "name" => $item->name,
            "description" => $item->description ?? $item->name,
        ]);

        return Inertia::render('Programs/Index', ['programs' => $programs, 'search' => $search,]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProgramRequest $request)
    {
        $program = new Program;

        $program->name = $request->name;

        $program->save();

        return redirect()
            ->back()
            ->with('success', 'Program added');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProgramRequest $request, Program $program)
    {
        $program = Program::find($request->id);

        $program->name = $request->name;

        $program->save();

        return redirect()
            ->back()
            ->with('success', 'Program updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Program $program)
    {
        $program->delete();

        return redirect()
            ->back()
            ->with('global-success', 'Program deleted');
    }
}
