<?php

namespace App\Http\Controllers\V1;

use App\Models\Term;
use Inertia\Inertia;
use App\Models\Staff;
use App\Models\Intake;
use App\Models\Lesson;
use App\Models\Subject;
use App\Models\Allocation;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreAllocationRequest;
use App\Http\Requests\V1\AllocationLessonRequest;
use App\Http\Requests\V1\UpdateAllocationRequest;

class AllocationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $search = request()->input('search');

        $allocations = Allocation::when($search, function ($query) use ($search) {
            $query->whereHas('staff', function ($query) use ($search) {
                $query->where('surname', 'LIKE', "%$search%")
                    ->orWhere('first_name', 'LIKE', "%$search%")
                    ->orWhere('middle_name', 'LIKE', "%$search%");
            })->orWhereHas('subject', function ($query) use ($search) {
                $query->where('name', 'LIKE', "%$search%");
                // })->orWhereHas('intakes', function ($query) use ($search) {
                //     $query->where('name', 'LIKE', "%$search%");
            })->orWhereHas('term', function ($query) use ($search) {
                $query->where('name', 'LIKE', "%$search%")
                    ->orWhere('year', $search);
            });
        })->orderBy('created_at', 'DESC')
            ->with('term', 'staff', 'subject', 'intakes', 'lessons')
            ->paginate(10)
            ->through(fn ($item) => [
                "id" => $item->id,
                "term" => [
                    "id" => $item->term->id,
                    "name" => $item->term->name,
                    "year" => $item->term->year,
                    "start_date" => $item->term->start_date->isoFormat('lll'),
                    "end_date" => $item->term->end_date->isoFormat('lll'),
                    "year_name" => $item->term->year_name,
                ],
                "instructor" => [
                    "id" => $item->staff->id,
                    "name" => Str::lower(sprintf("%s %s %s", $item->staff->surname, $item->staff->first_name, $item->staff->middle_name)),
                ],
                "subject" => [
                    "id" => $item->subject->id,
                    "code" => $item->subject->code,
                    "name" => $item->subject->name,
                ],
                "intakes" => $item->intakes->map(fn ($intake) => [
                    "id" => $intake->id,
                    "name" => $intake->name,
                ]),
                "lessons" => $item->lessons->map(fn ($lesson) => [
                    "id" => $lesson->id,
                    "title" => $lesson->title,
                    "day" => $lesson->day,
                    "start_at" => $lesson->start_at,
                    "end_at" => $lesson->end_at,
                ]),
            ]);

        $subjects = Subject::all()->map(fn ($item) => [
            "id" => $item->id,
            "code" => $item->code,
            "name" => ucwords(strtolower($item->name)),
        ]);

        $instructors = Staff::whereHas('status', function ($query) {
            $query->where('name', 'LIKE', '%current%');
        })
            // ->where('teach', 1)
            ->get()->map(fn ($item) => [
                "id" => $item->id,
                "name" => sprintf("%s %s %s", $item->first_name, $item->middle_name, $item->surname)
            ]);

        $terms = Term::orderBy('year', 'DESC')->orderBy('name', 'DESC')->get()->map(fn ($item) => [
            "id" => $item->id,
            "name" => $item->name,
            "year" => $item->year,
            "year_name" => $item->year_name,
            "start_date" => Carbon::parse($item->start_date)->isoFormat('lll'),
            "end_date" => Carbon::parse($item->end_date)->isoFormat('lll'),
        ]);

        $intakes = Intake::orderBy('start_date', 'DESC')->get()->map(fn ($item) => [
            "id" => $item->id,
            "name" => $item->name,
            "start_date" => $item->start_date,
            "end_date" => $item->end_date,
        ]);

        $lessons = Lesson::all();

        return Inertia::render('Allocations/Index', [
            'allocations' => $allocations,
            'subjects' => $subjects,
            'instructors' => $instructors,
            'terms' => $terms,
            'intakes' => $intakes,
            'lessons' => $lessons,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAllocationRequest $request)
    {
        $allocation = new Allocation();
        $allocation->staff_id = $request->instructor;
        $allocation->subject_id = $request->subject;
        $allocation->term_id = $request->term;
        $allocation->save();

        $allocation->intakes()->sync($request->intakes);
        return redirect()->back()->with('success', 'Allocation created');
    }

    /**
     * Display the specified resource.
     */
    public function show(Allocation $intakeStaffSubject)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Allocation $intakeStaffSubject)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAllocationRequest $request, Allocation $allocation)
    {
        $allocation->staff_id = $request->instructor;
        $allocation->subject_id = $request->subject;
        $allocation->term_id = $request->term;
        $allocation->save();

        $allocation->intakes()->sync($request->intakes);
        return redirect()->back()->with('success', 'Allocation updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Allocation $intakeStaffSubject)
    {
        //
    }

    function lessons(AllocationLessonRequest $request, Allocation $allocation)
    {
        $allocation->lessons()->sync($request->lessons);
        return redirect()->back()->with('success', 'Lessons schedule updated');
    }
}
