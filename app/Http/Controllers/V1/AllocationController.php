<?php

namespace App\Http\Controllers\V1;

use Inertia\Inertia;
use App\Models\Staff;
use App\Models\Course;
use App\Models\Allocation;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreAllocationRequest;
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
            })->orWhereHas('intake', function ($query) use ($search) {
                $query->where('name', 'LIKE', "%$search%");
            });
        })->orderBy('created_at', 'DESC')
            ->with('term', 'staff', 'subject', 'intakes')
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
                "staff" => [
                    "id" => $item->staff->id,
                    "name" => sprintf("%s %s %s", $item->staff->surname, $item->staff->first_name, $item->staff->middle_name),
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
            ]);

        $courses = Course::all();

        $instructors = Staff::whereHas('status', function ($query) {
            $query->where('name', 'LIKE', '%current%');
        })
            // ->where('teach', 1)
            ->get()->map(fn ($item) => [
                "id" => $item->id,
                "name" => sprintf("%s %s %s", $item->first_name, $item->middle_name, $item->surname)
            ]);

        return Inertia::render('Allocations/Index', ['allocations' => $allocations, 'courses' => $courses, 'instructors' => $instructors]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAllocationRequest $request)
    {
        //
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
    public function update(UpdateAllocationRequest $request, Allocation $intakeStaffSubject)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Allocation $intakeStaffSubject)
    {
        //
    }
}
