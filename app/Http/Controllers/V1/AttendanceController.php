<?php

namespace App\Http\Controllers\V1;

use Dompdf\Dompdf;
use Dompdf\Options;
use Inertia\Inertia;
use App\Models\Allocation;
use App\Models\Attendance;
use Illuminate\Support\Str;
use App\Exports\StudentExport;
use App\Models\AllocationLesson;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests\V1\StoreAttendanceRequest;
use App\Http\Requests\V1\UpdateAttendanceRequest;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $search = request()->input('search');
        $current = request()->input('current');

        $allocations = Allocation::with([
            'allocation_lessons' => function ($query) {
                $query->orderBy('lesson_id', 'ASC');
            },
            'term',
            'subject',
            'staff'
        ])
            ->whereHas('allocation_lessons')
            ->whereHas('term', function ($query) {
                if (request()->input('current')) {
                    $query->whereDate('end_date', '>', now());
                }
            })
            ->orderBy('created_at', 'DESC')
            ->paginate(8)
            ->through(fn($item) => [
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
                "intakes" => $item->intakes->map(fn($intake) => [
                    "id" => $intake->id,
                    "name" => $intake->name,
                ]),
                "lessons" => $item->allocation_lessons->map(fn($al) => [
                    "id" => $al->id,
                    "title" => $al->lesson->title,
                    "day" => $al->lesson->day,
                    "start_at" => $al->lesson->start_at,
                    "end_at" => $al->lesson->end_at,
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
    public function store(StoreAttendanceRequest $request)
    {
        if ($request->hasFile('upload')) {

            return redirect()->back()->with('success', 'Attendance uploaded');
        }
        $attendance = new Attendance();

        $attendance->allocation_lesson_id = $request->allocation_lesson_id;

        return redirect()->back()->with('success', 'Attendance uploaded');
    }

    /**
     * Display the specified resource.
     */
    public function showMark(AllocationLesson $allocation_lesson)
    {
        $allocation_lesson->load('allocation.intakes.students', 'allocation.term', 'allocation.staff', 'allocation.subject');

        $students = collect();

        foreach ($allocation_lesson->allocation->intakes as $intake) {
            foreach ($intake->students as $student) {
                $students->push([
                    "id" => $student->id,
                    "admission_no" => $student->admission_no,
                    "name" => $student->name,
                    "gender" => $student->gender ? "Female" : "Male",
                ]);
            }
        }
        return Inertia::render('Attendances/Mark', [
            'lesson' => [
                "id" => $allocation_lesson->id,
                "title" => $allocation_lesson->lesson->title,
                "term" => sprintf("%s-%s", $allocation_lesson->allocation->term->year, $allocation_lesson->allocation->term->name),
                "instructor" => trim(
                    sprintf(
                        "%s%s%s",
                        " " . $allocation_lesson->allocation->staff->first_name,
                        " " . $allocation_lesson->allocation->staff->middle,
                        " " . $allocation_lesson->allocation->staff->surname
                    )
                ),
                "subject" => $allocation_lesson->allocation->subject->name,
                "intakes" => implode(", ", $allocation_lesson->allocation->intakes->pluck('name')->toArray()),
                "students" => $students
            ]
        ]);
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
    public function update(UpdateAttendanceRequest $request, Allocation $allocation)
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
     * @param string $type
     * @return void
     */
    function download(Allocation $allocation, string $type)
    {

        $students = null;
        $filename = "";
        if ($allocation) {
            $allocation->load('intakes.students');
            $allocation->load('staff');
            $allocation->load('term');
            $filename = Str::upper(Str::lower(Str::replace(" ", "_", sprintf("%s %s %s", $allocation->subject->code, $allocation->term->year, $allocation->term->name))));
            $students = $allocation->intakes->flatMap->students->map(function ($item) {
                return (object) [
                    "admission_no" => $item->admission_no,
                    "name" => $item->name, //Str::upper(Str::lower(sprintf("%s%s%s", $item->first_name, $item->middle_name, $item->surname)))
                    "gender" => $item->gender ? 'Female' : 'Male'
                ];
            });

            if ($type == 'excel') {
                return Excel::download(new StudentExport($students, $allocation), $filename . '.xlsx');
            } else {

                // Fetch logo paths from the configuration
                $logos = collect(config('eschool.logo'))->map(function ($logo) {
                    return sprintf("data:image/png;base64,%s", base64_encode(file_get_contents(public_path($logo))));
                });

                // Read the content of app.css
                $styles = file_get_contents(public_path('css/pdf.css'));

                // Generate PDF content
                $pdfContent = view('pdf.students', [
                    'students' => $students,
                    'allocation' => $allocation,
                    'logos' => $logos,
                    'styles' => $styles,
                ])->render();

                $options = new Options();
                $options->set('isPhpEnabled', true);

                // Create PDF
                $dompdf = new Dompdf($options);
                $dompdf->loadHtml($pdfContent);
                $dompdf->setPaper('A4', 'landscape');
                // Render PDF
                $dompdf->render();

                // Output PDF
                return $dompdf->stream($filename . '.pdf');
            }
        }
        return redirect()->back()->with('error', 'Allocation not found.');
    }
}
