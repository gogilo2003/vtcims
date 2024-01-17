<?php

namespace App\Http\Controllers\V1;

use Inertia\Inertia;
use App\Models\Staff;
use App\Models\Course;
use App\Models\Intake;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class IntakeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $search = request()->input('search');

        $intakes = Intake::when($search, function ($query) use ($search) {
            $query->where('name', 'LIKE', '%' . $search . '%');
        })->with(['staff', 'course'])->paginate(10)->through(fn ($item) => [
            "id" => $item->id,
            "name" => $item->name,
            "staff" => [
                "id" => $item->staff->id,
                "name" => trim(sprintf(
                    '%s %s %s',
                    $item->staff->surname,
                    $item->staff->first_name,
                    $item->staff->middle_name
                )),
            ],
            "course" => [
                "id" => $item->course->id,
                "code" => $item->course->code,
                "name" => $item->course->name,
            ],
        ]);

        $instructors = Staff::whereHas('status', function ($query) {
            $query->where('name', 'current');
        })->where('teach', 1)->get()->map(fn ($item) => [
            "id" => $item->id,
            "name" => sprintf("%s %s %s", $item->surname, $item->last_name, $item->middle_name),
        ]);

        $courses = Course::orderBy('name', 'ASC')->get()->map(fn ($item) => [
            "id" => $item->id,
            "code" => $item->code,
            "name" => $item->name,
        ]);

        return Inertia::render('Intakes/Index', ['intakes' => $intakes, 'instructors' => $instructors, 'courses' => $courses, 'search' => $search,]);
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Intake $intake)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Intake $intake)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Intake $intake)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Intake $intake)
    {
        //
    }
}
