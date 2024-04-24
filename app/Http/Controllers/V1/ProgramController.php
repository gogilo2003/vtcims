<?php

namespace App\Http\Controllers\V1;

use Inertia\Inertia;
use App\Models\Program;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreProgramRequest;
use App\Http\Requests\V1\UpdateProgramRequest;

class ProgramController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        $search = request()->input('search');

        $programs = Program::when($search, function ($query) use ($search) {
            $query->where('name', 'LIKE', '%' . $search . '%');
        })->paginate(8)->through(fn($item) => [
                "id" => $item->id,
                "name" => $item->name,
                "description" => $item->description ?? $item->name,
            ]);

        return Inertia::render('Programs/Index', ['programs' => $programs, 'search' => $search,]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\V1\StoreProgramRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreProgramRequest $request)
    {
        $program = new Program;

        $program->name = $request->name;
        $program->description = $request->description;

        $program->save();

        return redirect()
            ->back()
            ->with('success', 'Program added');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\V1\UpdateProgramRequest $request
     * @param \App\Models\Program $program
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateProgramRequest $request, Program $program)
    {

        $program->name = $request->name;
        $program->description = $request->description;

        $program->save();

        return redirect()
            ->back()
            ->with('success', 'Program updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Program $program
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Program $program)
    {
        $program->delete();

        return redirect()
            ->back()
            ->with('global-success', 'Program deleted');
    }
}
