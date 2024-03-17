<?php

namespace App\Http\Controllers\V1;

use App\Models\Term;
use Inertia\Inertia;
use App\Models\Staff;
use App\Models\Intake;
use App\Models\Subject;
use App\Models\Allocation;
use App\Models\Attendance;
use Illuminate\Support\Str;
use App\Exports\StudentExport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests\V1\StoreAllocationRequest;
use App\Http\Requests\V1\UpdateAllocationRequest;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $search = request()->input('search');
        $current = request()->input('current');

        $allocations = Allocation::with('attendances.students', 'attendances.lessons', 'term', 'subject', 'staff')
            ->whereHas('term', function ($query) {
                if (request()->input('current')) {
                    $query->whereDate('end_date', '>', now());
                }
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
            ]);

        return Inertia::render('Attendances/Index', [
            'allocations' => $allocations,
            'search' => $search,
            'current' => $current,
        ]);
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

    /**
     * Download
     *
     * Download attendance list for a particular class/allocation in excel or pdf
     *
     * @param String $type
     * @return void
     */
    function download(Allocation $allocation, String $type)
    {

        $students = null;
        $filename = "";
        if ($allocation) {
            $allocation->load('intakes.students');
            $allocation->load('staff');
            $allocation->load('term');
            $filename = Str::upper(Str::lower(Str::replace(" ", "_", sprintf("%s %s %s", $allocation->subject->code, $allocation->term->year, $allocation->term->name))));
            $students = $allocation->intakes->flatMap->students->map(function ($item) {
                return [
                    "admission_no" => $item->admission_no,
                    "name" => $item->name, //Str::upper(Str::lower(sprintf("%s%s%s", $item->first_name, $item->middle_name, $item->surname)))
                    "gender" => $item->gender ? 'Female' : 'Male'
                ];
            });
        } else {
            // Allocation not found
        }

        if ($type == 'excel') {
            return Excel::download(new StudentExport($students, $allocation), $filename . '.xlsx');
        } else {
            // return pdf
        }
    }
}
