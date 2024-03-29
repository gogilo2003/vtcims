<?php

namespace App\Http\Controllers\V1;

use Inertia\Inertia;
use App\Models\Staff;
use App\Models\Course;
use App\Models\Intake;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreIntakeRequest;
use App\Http\Requests\V1\UpdateIntakeRequest;

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
        })->with(['staff', 'course'])
            ->orderBy('created_at', 'DESC')
            ->orderBy('name', 'ASC')
            ->paginate(10)->through(fn ($item) => [
                "id" => $item->id,
                "name" => $item->name,
                "start_date" => $item->start_date,
                "end_date" => $item->end_date,
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
     * Store a newly created resource in storage.
     */
    public function store(StoreIntakeRequest $request)
    {
        $intake = new Intake;
        $intake->start_date = Carbon::parse($request->start_date);
        $intake->end_date = Carbon::parse($request->end_date);
        $intake->staff_id = $request->instructor;
        $intake->course_id = $request->course;
        $intake->name = $request->name;
        $intake->save();

        return redirect()
            ->back()
            ->with('success', 'Class/Intake created');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateIntakeRequest $request, Intake $intake)
    {
        $intake->start_date = Carbon::parse($request->start_date);
        $intake->end_date = Carbon::parse($request->end_date);
        $intake->staff_id = $request->instructor;
        $intake->course_id = $request->course;
        $intake->name = $request->name;
        $intake->save();

        return redirect()
            ->back()
            ->with('success', 'Class/Intake updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Intake $intake)
    {
        //
    }
}
