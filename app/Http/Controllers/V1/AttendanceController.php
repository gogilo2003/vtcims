<?php

namespace App\Http\Controllers\V1;

use DateTime;
use DatePeriod;
use DateInterval;
use App\Models\Term;
use Inertia\Inertia;
use App\Models\Staff;
use App\Models\Lesson;
use App\Models\Student;
use App\Models\Allocation;
use App\Models\Attendance;
use Illuminate\Support\Str;
use App\Support\StudentUtil;
use App\Exports\StudentExport;
use Illuminate\Support\Carbon;
use App\Models\AllocationLesson;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Database\Eloquent\Collection;
use App\Http\Requests\V1\StoreAttendanceRequest;
use App\Http\Requests\V1\UploadAttendanceRequest;

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
            'staff',
            'attendances.allocation_lesson',
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
                'attendances' => $item->attendances->map(fn($item) => (object) [
                    "id" => $item->id,
                    "attendance_date" => Carbon::parse($item->attendance_date)->isoFormat('ddd, D MMM Y'),
                    "allocation_lesson" => $item->allocation_lesson
                ]),
                "term" => [
                    "id" => $item->term->id,
                    "name" => $item->term->name,
                    "year" => $item->term->year,
                    "start_date" => $item->term->start_date->isoFormat('lll'),
                    "end_date" => $item->term->end_date->isoFormat('lll'),
                ],
                "instructor" => [
                    "id" => $item->staff->id,
                    "name" => Str::lower(sprintf("%s %s", $item->staff->first_name, $item->staff->surname ?? $item->staff->middle_name)),
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
                    "term" => sprintf("%s-%s", $al->allocation->term->year, $al->allocation->term->name),
                    "instructor" => trim(
                        sprintf(
                            "%s%s%s",
                            " " . $al->allocation->staff->first_name,
                            " " . $al->allocation->staff->middle,
                            " " . $al->allocation->staff->surname
                        )
                    ),
                    "subject" => $al->allocation->subject->name,
                    "intakes" => implode(", ", $al->allocation->intakes->pluck('name')->toArray()),
                ]),
            ]);

        $terms = Term::get()->map(fn(Term $term) => [
            'id' => $term->id,
            'name' => sprintf("%d - %s", $term->year, $term->name),
        ])->sortByDesc('name')->values();

        return Inertia::render('Attendances/Index', [
            'allocations' => $allocations,
            'search' => $search,
            'current' => $current,
            'terms' => $terms,
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
    public function mark(StoreAttendanceRequest $request)
    {
        $attendance = new Attendance();

        $attendance->allocation_lesson_id = $request->allocation;

        $attendance->attendance_date = Carbon::parse($request->mark_at);

        $attendance->save();

        $attendance->students()->sync($request->students);

        return redirect()->back()->with('success', 'Attendance uploaded');
    }
    public function upload(UploadAttendanceRequest $request)
    {
        // Parse the uploaded Excel file
        $file = $request->file('file');
        $data = Excel::toCollection((object) [], $file)[0]; // Assuming data is in the first sheet

        $allocationLessonId = $data[0][1];
        $allocation_lesson = AllocationLesson::find($request->allocation);

        if ($allocationLessonId !== $allocation_lesson->allocation->id) {
            return redirect()->back()->with('danger', 'You are attempting to upload data for the wrong class');
        }

        $attendance = new Attendance();
        $attendance->allocation_lesson_id = $request->allocation;
        $attendance->attendance_date = Carbon::parse($request->mark_at);
        $attendance->save();

        $data = collect($data->splice(7));
        $students = $data->filter(fn($item) => $item[3])->map(fn($item) => (int) explode("/", $item[0])[1])->toArray();

        $attendance->students()->sync($students);

        return redirect()->back()->with('success', 'Attendance uploaded successfully.');

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
                    "admission_no" => StudentUtil::prepAdmissionNo($student),
                    "name" => sprintf(
                        "%s%s %s",
                        $student->first_name,
                        $student->middle_name ? ' ' . $student->middle_name : '',
                        $student->surname
                    ),
                    "gender" => $student->gender ? "Female" : "Male",
                ]);
            }
        }
        $day = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];

        return Inertia::render('Attendances/Mark', [
            'lesson' => [
                "id" => $allocation_lesson->id,
                "title" => $allocation_lesson->lesson->title,
                "day" => array_search($allocation_lesson->lesson->day, $day),
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
     * Download excel template
     * @param \App\Models\Allocation $allocation
     * @return \Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    function downloadExcel(Allocation $allocation)
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

            return Excel::download(new StudentExport($students, $allocation), $filename . '.xlsx');

        }
        return redirect()->back()->with('error', 'Allocation not found.');
    }

    /**
     * Download pdf attendance register
     * @param \App\Models\Allocation $allocation
     * @return \Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    function downloadPdf(Allocation $allocation)
    {
        $students = null;
        $filename = "";

        if ($allocation) {
            $allocation->load([
                'intakes.students' => function ($query) {
                    $query->where('status', 'In Session');
                }
            ]);
            $allocation->load('staff');
            $allocation->load('term');
            $allocation->load('subject');

            $filename = Str::upper(Str::lower(Str::replace(" ", "_", sprintf("%s %s %s", $allocation->subject->code, $allocation->term->year, $allocation->term->name))));
            $students = $allocation->intakes
                ->flatMap
                ->students
                ->map(function (Student $item) {
                    return (object) [
                        "admission_no" => StudentUtil::prepAdmissionNo($item),
                        "name" => Str::upper(
                            Str::lower(
                                sprintf(
                                    "%s%s %s",
                                    $item->first_name,
                                    $item->middle_name ? ' ' . $item->middle_name : '',
                                    $item->surname,
                                )
                            )
                        ), //Str::upper(Str::lower(sprintf("%s%s%s", $item->first_name, $item->middle_name, $item->surname)))
                        "gender" => $item->gender ? 'Female' : 'Male',
                        "plwd" => $item->plwd ? 'Yes' : 'No'
                    ];
                });

            // Fetch logo paths from the configuration
            $logos = collect(config('eschool.logo'))->map(function ($logo) {
                return sprintf("data:image/png;base64,%s", base64_encode(file_get_contents(public_path($logo))));
            });

            // Read the content of app.css
            $styles = file_get_contents(public_path('css/pdf.css'));

            $allocationData = $this->getAllocationData($allocation);

            // Generate PDF content
            $pdfContent = "";
            $principal = Staff::whereHas('status', function ($query) {
                $query->where('name', 'like', '%current%');
            })->whereHas('role', function ($query) {
                $query->where('name', 'like', 'principal');
            })->first();

            if (!$principal) {
                $principal = Staff::whereHas('status', function ($query) {
                    $query->where('name', 'like', '%current%');
                })->whereHas('role', function ($query) {
                    $query->where('name', 'like', '%principal%');
                })->first();
            }

            if (request()->input('duration') == 'week') {
                $date = request()->input('start_at') ? request()->input('start_at') : (new DateTime('monday this week'))->format('Y-m-d');
                $startOfWeek = Carbon::parse($date)->isoFormat('ddd, D MMM Y');
                $endOfWeek = Carbon::parse($date)->addWeek()->isoFormat('ddd, D MMM Y');
                $viewName = 'pdf.students.attendance';
                if (file_exists(resource_path('views/custom/students/attendance'))) {
                    $viewName = 'pdf.custom.students.attendance';
                }
                $pdfContent = view()->first(['pdf.custom.students.attendance', 'pdf.students.attendance'], [
                    'students' => $students,
                    'allocation' => $allocationData,
                    'logos' => $logos,
                    'styles' => $styles,
                    "start_date" => $startOfWeek,
                    "end_date" => $endOfWeek,
                    "principal" => $principal,
                ])->render();
            } elseif (request()->input('duration') == 'month') {
                $currentMonth = request()->has('month')
                    ? Carbon::parse(request()->input('month'))->setTimezone('Africa/Nairobi')->isoFormat('Y-M')
                    : date('Y-m');
                $startOfMonth = Carbon::parse(date('Y-m-01', strtotime($currentMonth)))->isoFormat('ddd, D MMM Y');
                $endOfMonth = Carbon::parse(date('Y-m-t', strtotime($currentMonth)))->isoFormat('ddd, D MMM Y');
                $viewName = 'pdf.students.attendance';
                if (file_exists(resource_path('views/pdf/custom/students.attendance'))) {
                    $viewName = 'pdf.custom.students.attendance';
                }
                $pdfContent = view($viewName, [
                    'students' => $students,
                    'allocation' => $allocationData,
                    'logos' => $logos,
                    'styles' => $styles,
                    "start_date" => $startOfMonth,
                    "end_date" => $endOfMonth,
                    "principal" => $principal,
                ])->render();
            } elseif (request()->input('duration') == 'term') {
                $currentQuarter = ceil(date('n') / 3);
                $startOfQuarter = Carbon::parse(date('Y-m-d', mktime(0, 0, 0, ($currentQuarter - 1) * 3 + 1, 1)))->isoFormat('ddd, D MMM Y');
                $endOfQuarter = Carbon::parse(date('Y-m-d', mktime(0, 0, 0, $currentQuarter * 3, 0)))->isoFormat('ddd, D MMM Y');
                if (request()->has('term')) {
                    $term = Term::find(request()->input('term'));
                    $startOfQuarter = Carbon::parse($term->start_date)->isoFormat('ddd, D MMM Y');
                    $endOfQuarter = Carbon::parse($term->end_date)->isoFormat('ddd, D MMM Y');
                }
                $viewName = 'pdf.students.attendance';
                if (file_exists(resource_path('views/pdf/custom/students.attendance'))) {
                    $viewName = 'pdf.custom.students.attendance';
                }
                $pdfContent = view($viewName, [
                    'students' => $students,
                    'allocation' => $allocationData,
                    'logos' => $logos,
                    'styles' => $styles,
                    "start_date" => $startOfQuarter,
                    "end_date" => $endOfQuarter,
                    "principal" => $principal,
                ])->render();
            }

            $pdf = App::make('snappy.pdf.wrapper')
                ->setOrientation('landscape')
                ->setPaper('A4')
                ->setOption('no-outline', true)
                ->setOption('footer-center', 'Page [page] of [toPage]')
                ->setOption('footer-font-size', 8);

            // return response($pdfContent);
            $pdf->loadHTML($pdfContent);
            return $pdf->download($filename . '.pdf', ["Attachment" => 0]);

        }
        return redirect()->back()->with('error', 'Allocation not found.');
    }

    protected function getDatesForDaysInPeriod(array $days, $start, $end)
    {
        $dates = [];
        $startDate = new DateTime($start);
        $endDate = new DateTime($end);

        $interval = DateInterval::createFromDateString('1 week');
        $period = new DatePeriod($startDate, $interval, $endDate);

        foreach ($period as $date) {
            foreach ($days as $day) {
                $date->modify("this $day");
                if ($date >= $startDate && $date <= $endDate) {
                    $dates[] = Carbon::parse($date->format('Y-m-d'))->isoFormat('ddd, D MMM Y');
                }
            }
        }
        return $dates;
    }

    protected function getDatesForToday(array $days)
    {
        $startOfWeek = (new DateTime('today'))->format('Y-m-d');
        $endOfWeek = date('Y-m-d', strtotime('today'));
        return $this->getDatesForDaysInPeriod($days, $startOfWeek, $endOfWeek);
    }

    protected function getDatesForDaysInCurrentWeek(array $days)
    {
        // $startOfWeek = (new DateTime('monday this week'))->format('Y-m-d');
        // $endOfWeek = date('Y-m-d', strtotime('friday this week'));
        $date = request()->input('start_at') ? request()->input('start_at') : (new DateTime('monday this week'))->format('Y-m-d');
        $startOfWeek = Carbon::parse((new DateTime($date))->format('Y-m-d'))->isoFormat('ddd, D MMM Y');
        $endOfWeek = Carbon::parse(new DateTime($date))->addWeek()->isoFormat('ddd, D MMM Y');
        return $this->getDatesForDaysInPeriod($days, $startOfWeek, $endOfWeek);
    }

    protected function getDatesForDaysInCurrentMonth(array $days)
    {
        $currentMonth = date('Y-m');
        $start = date('Y-m-01', strtotime($currentMonth));
        $end = date('Y-m-t', strtotime($currentMonth));
        return $this->getDatesForDaysInPeriod($days, $start, $end);
    }

    function getDatesForDaysInCurrentTerm(array $days)
    {
        $currentQuarter = ceil(date('n') / 4);
        $start = date('Y-m-d', mktime(0, 0, 0, ($currentQuarter - 1) * 3 + 1, 1));
        $end = date('Y-m-d', mktime(0, 0, 0, $currentQuarter * 3, 0));

        return $this->getDatesForDaysInPeriod($days, $start, $end);
    }

    protected function lessonData(Collection $lessons, string $duration)
    {

        $days = $lessons->unique('day')->pluck('day')->toArray();
        $dates = null;

        if ($duration == 'day') {
            $dates = $this->getDatesForToday($days);
        } elseif ($duration == 'week') {
            $dates = $this->getDatesForDaysInCurrentWeek($days);
        } elseif ($duration == 'month') {
            $dates = $this->getDatesForDaysInCurrentMonth($days);
        } elseif ($duration == 'term') {
            $dates = $this->getDatesForDaysInCurrentTerm($days);
        }
        $lessonData = [];

        foreach ($dates as $date) {
            $lessonData[] = (object) [
                "day" => Carbon::parse($date)->isoFormat('dddd'),
                "date" => Carbon::parse($date)->isoFormat('ddd, D MMM Y'),
                "lessons" => $lessons->where('day', Carbon::parse($date)->isoFormat('dddd'))->map(function ($lesson) {
                    $arr = explode("-", trim($lesson->title));
                    $short_title = Str::replace("Lesson ", "L", trim($arr[1]));
                    return (object) [
                        "title" => $lesson->title,
                        "short_title" => $short_title,
                        "start_at" => $lesson->start_at->isoFormat('h:m:s A'),
                        "end_at" => $lesson->end_at->isoFormat('h:m:s A'),
                    ];
                })->sortBy('short_title')->values(),
            ];
        }

        return collect($lessonData);
    }

    private function getAllocationData(Allocation $allocation)
    {
        return (object) [
            "staff" => $allocation->staff,
            "subject" => $allocation->subject,
            "term" => $allocation->term,
            "intakes" => $allocation->intakes,
            "lessons" => $this->lessonData($allocation->lessons, request()->input("duration")),
        ];
    }
}
