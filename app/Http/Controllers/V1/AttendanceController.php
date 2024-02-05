<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAllocationRequest;
use App\Http\Requests\UpdateAllocationRequest;
use App\Models\Allocation;
use App\Models\Attendance;
use Inertia\Inertia;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $allocations = Allocation::with('attendances.students', 'attendances.lessons', 'term', 'subject', 'staff')
            ->whereHas('term', function ($query) {
                $query->whereDate('end_date', '>', now());
            })
            ->orderBy('created_at', 'DESC')
            ->paginate(8)
            ->through(fn ($item) => [
                "id" => $item->id,
                'attendances' => $item->attendances,
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
        return Inertia::render('Attendances/Index', ['allocations' => $allocations]);
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
    public function store(StoreAllocationRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Allocation $allocation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Allocation $allocation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAllocationRequest $request, Allocation $allocation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Allocation $allocation)
    {
        //
    }
}
