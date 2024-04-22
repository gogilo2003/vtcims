<?php

namespace App\Http\Controllers\V1;

use Inertia\Inertia;
use App\Models\Course;
use App\Models\Intake;
use App\Models\Program;
use App\Models\Sponsor;
use App\Models\Student;
use App\Models\Department;
use App\Models\StudentRole;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\V1\StoreStudentRequest;
use App\Http\Requests\V1\UpdateStudentRequest;
use App\Http\Requests\V1\UploadPictureRequest;

class StudentController extends Controller
{
    /**
     * Fetch students' records from the database based on search value provided or all students
     * sorted by date
     *
     * @return \Inertia\Response
     */
    function index()
    {

        $students = null;

        $search = request()->input('search');

        $students = Student::with([
            'intake.course',
            'program',
            'sponsor',
            'intake.examinations' => function ($query) {
                $query->with(['term', 'subject', 'tests.results'])->orderBy('term_id', 'DESC');
            }
        ])->orderBy('id', 'DESC')
            ->when(
                $search,
                function ($query) use ($search) {
                    $names = explode(" ", $search);
                    foreach ($names as $name) {
                        $query->where(function ($query) use ($name) {
                            $query->where('surname', 'like', '%' . $name . '%')
                                ->orWhere('first_name', 'like', '%' . $name . '%')
                                ->orWhere('middle_name', 'like', '%' . $name . '%');
                        });
                    }
                }
            )->paginate(8)->through(
                function ($student) {
                    $id = $student->id;
                    $examinations = $student->intake->examinations->map(function ($exam) use ($id) {
                        $exam->load([
                            'term',
                            'subject',
                            'tests.results' => function ($query) use ($id) {
                                $query->where('student_id', $id);
                            }
                        ]);

                        $score = 0;

                        foreach ($exam->tests as $test) {
                            foreach ($test->results as $result) {
                                $score += $result->score;
                            }
                        }

                        $data = (object) [
                            "subject" => sprintf(
                                "%s/%s - %s",
                                $exam->term->year,
                                $exam->term->name,
                                $exam->subject->name
                            ),
                            "score" => $score,
                        ];

                        return $data;
                    })->groupBy('subject')->map(function ($marks, $key) {
                        return [
                            "subject" => $key,
                            "score" => number_format($marks->avg('score'), 2),
                            "total" => number_format($marks->sum('score'), 2),
                            "max" => number_format($marks->max('score'), 2),
                            "min" => number_format($marks->min('score'), 2),
                        ];
                    })->sortBy('subject')->values();

                    return (object) [
                        "id" => $student->id,
                        "admission_no" => $student->intake
                            ? strtoupper(
                                $student->intake->course->code . '/'
                                . str_pad($student->id, 4, '0', 0) . '/'
                                . $student->date_of_admission->format('Y')
                            )
                            : '',
                        "photo" => $student->photo ?? "",
                        "photo_url" => $student->photo ? Storage::disk('public')->url($student->photo) : asset('img/person_8x10.png'),
                        "surname" => ucfirst(Str::lower($student->surname)),
                        "first_name" => ucfirst(Str::lower($student->first_name)),
                        "middle_name" => ucfirst(Str::lower($student->middle_name)),
                        "phone" => $student->phone ?? "",
                        "email" => $student->email ?? "",
                        "box_no" => $student->box_no ?? "",
                        "post_code" => $student->post_code ?? "",
                        "town" => $student->town ?? "",
                        "physical_address" => $student->physical_address ?? "",
                        "date_of_birth" => $student->date_of_birth ? $student->date_of_birth->isoFormat("ddd, D MMM, Y") : '',
                        "birth_cert_no" => $student->birth_cert_no ?? "",
                        "idno" => $student->idno ?? "",
                        "gender" => $student->gender,
                        "date_of_admission" => $student->date_of_admission ? $student->date_of_admission->isoFormat('ddd, D MMM, Y') : '',
                        "intake" => [
                            "id" => $student->intake->id,
                            "name" => $student->intake->name,
                            "course" => $student->intake->course->name,
                        ],
                        "program" => [
                            "id" => $student->program->id,
                            "name" => $student->program->name,
                            "description" => $student->program->description ?? "",
                        ],
                        "sponsor" => [
                            "id" => $student->sponsor->id,
                            "name" => $student->sponsor->name,
                            "contact_person" => $student->sponsor->contact_person ?? "",
                            "email" => $student->sponsor->email ?? "",
                            "phone" => $student->sponsor->phone ?? "",
                            "box_no" => $student->sponsor->box_no ?? "",
                            "post_code" => $student->sponsor->post_code ?? "",
                            "town" => $student->sponsor->town ?? "",
                            "address" => $student->sponsor->address ?? "",
                        ],
                        "role" => [
                            "id" => $student->intake->id,
                            "name" => $student->intake->name,
                            "description" => $student->intake->description,
                        ],
                        "status" => $student->status,
                        "plwd" => $student->plwd,
                        "plwd_details" => $student->plwd_details,
                        "examinations" => $examinations,
                    ];
                }
            );

        $departments = Department::orderBy('name', 'DESC')->get()->map(fn($item) => [
            "id" => $item->id,
            "name" => $item->name
        ]);
        $courses = Course::when($department = request()->input('d'), function ($query) use ($department) {
            $query->where('department_id', $department);
        })->orderBy('name', 'DESC')->get()->map(fn($item) => [
                "id" => $item->id,
                "name" => $item->name
            ]);
        $intakes = Intake::when($department = request()->input('d'), function ($query) use ($department) {
            $query->whereHas('course', function ($query) use ($department) {
                $query->where('department_id', $department);
            });
        })->when($course = request()->input('c'), function ($query) use ($course) {
            $query->where('course_id', $course);
        })->orderBy('name', 'DESC')->get()->map(fn($item) => [
                "id" => $item->id,
                "name" => $item->name
            ]);
        $programs = Program::orderBy('name', 'DESC')->get()->map(fn($item) => [
            "id" => $item->id,
            "name" => $item->name
        ]);
        $sponsors = Sponsor::orderBy('name', 'DESC')->get()->map(fn($item) => [
            "id" => $item->id,
            "name" => $item->name
        ]);
        $roles = StudentRole::orderBy('name', 'DESC')->get()->map(fn($item) => [
            "id" => $item->id,
            "name" => $item->name
        ]);

        return Inertia::render('Students/Index', [
            'students' => $students,
            'departments' => $departments,
            'courses' => $courses,
            'intakes' => $intakes,
            'programs' => $programs,
            'sponsors' => $sponsors,
            'roles' => $roles,
            'search' => $search,
        ]);
    }

    /**
     * Store a new student details
     *
     * @param StoreStudentRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    function store(StoreStudentRequest $request)
    {
        $student = new Student;

        $student->surname = $request->surname;
        $student->first_name = $request->first_name;
        $student->middle_name = $request->middle_name;
        $student->phone = $request->phone ? $request->phone : null;
        $student->email = $request->email;
        $student->box_no = $request->box_no;
        $student->post_code = $request->post_code;
        $student->town = $request->town;
        $student->date_of_birth = Carbon::parse($request->date_of_birth);
        $student->birth_cert_no = $request->birth_certificate_no;
        $student->idno = $request->idno;
        $student->gender = $request->gender;
        $student->date_of_admission = Carbon::parse($request->date_of_admission);
        $student->intake_id = $request->intake;
        $student->program_id = $request->program;
        $student->sponsor_id = $request->sponsor;
        $student->student_role_id = $request->role;
        $student->status = $request->status;
        $student->plwd = $request->plwd;
        $student->plwd_details = $request->plwd_details;

        $student->save();
        return redirect()->back()->with('success', 'Student created');
    }
    /**
     * Update selected student details
     *
     * @param UpdateStudentRequest $request
     * @param Student $student
     * @return \Illuminate\Http\RedirectResponse
     */
    function update(UpdateStudentRequest $request, Student $student)
    {
        $student->surname = $request->surname;
        $student->first_name = $request->first_name;
        $student->middle_name = $request->middle_name;
        $student->phone = $request->phone ? $request->phone : null;
        $student->email = $request->email;
        $student->box_no = $request->box_no;
        $student->post_code = $request->post_code;
        $student->town = $request->town;
        $student->date_of_birth = Carbon::parse($request->date_of_birth);
        $student->birth_cert_no = $request->birth_cert_no;
        $student->idno = $request->idno;
        $student->gender = $request->gender;
        $student->date_of_admission = Carbon::parse($request->date_of_admission);
        $student->intake_id = $request->intake;
        $student->program_id = $request->program;
        $student->sponsor_id = $request->sponsor;
        $student->student_role_id = $request->role;
        $student->status = $request->status;
        $student->plwd = $request->plwd;
        $student->plwd_details = $request->plwd_details;

        $student->save();

        return redirect()->back()->with('success', 'Student details updated');
    }

    /**
     * Store/Update student's uploaded picture
     *
     * @param UploadPictureRequest $request
     * @param Student $student
     * @return \Illuminate\Http\RedirectResponse
     */
    function picture(UploadPictureRequest $request, Student $student)
    {
        if ($request->hasFile('photo')) {
            $file = $request->photo;

            if ($file->isValid()) {
                if ($student->photo) {
                    if (Storage::disk('public')->exists($student->photo)) {
                        Storage::disk('public')->delete($student->photo);
                    }
                }

                $student->photo = $file->storePublicly('students', ["disk" => "public"]);

                $student->save();

                return redirect()
                    ->back()
                    ->with('success', 'Picture uploaded');
            }
            return redirect()->back()->with('error', 'An invalid picture file detected');
        }
        return redirect()->back()->with('error', 'No File has been uploaded');
    }

    function download($id = null)
    {
        $pdf = App::make('snappy.pdf.wrapper')
            ->setOrientation('landscape')
            ->setPaper('A4')
            ->setOption('no-outline', true);

        if ($id) {
            $student = Student::findOrFail($id);

            $pdf->loadView('pdf.students.view', compact('student'));
            // return view('pdf.students.view',compact('student'));
            $filename = str_replace('/', '_', $student->admission_no) . '.pdf';
            return $pdf->download($filename);
        } else {
            $department = request()->input('d');
            $course = request()->input('c');
            $intake = request()->input('i');

            $gender = request()->has('g') ? request()->input('g') : null;
            $sponsor = request()->has('sp') ? request()->input('sp') : null;
            $program = request()->has('pr') ? request()->input('pr') : null;
            $role = request()->has('r') ? request()->input('r') : null;
            $date_of_admission = request()->has('da') ? Carbon::parse(request()->input('da')) : null;
            $status = request()->has('su') ? request()->input('su') : null;

            $date_of_birth = null;
            if (request()->age > 0) {
                $date_of_birth = now();
                date_sub($date_of_birth, date_interval_create_from_date_string(request()->age . ' years'));
            }

            // dd($department, $course, $intake, $gender, $sponsor, $program, $role, $before_after, $date_of_admission, $date_of_birth);

            $students = Student::with([
                'intake.course',
                "sponsor",
                "program"
            ])
                ->orderBy('id', 'DESC')
                ->when($department, function ($query) use ($department) {
                    return $query->whereHas('intake.course', function ($query) use ($department) {
                        $query->where('department_id', $department);
                    });
                })
                ->when($course, function ($query) use ($course) {
                    return $query->whereHas('intake', function ($query) use ($course) {
                        $query->where('course_id', $course);
                    });
                })
                ->when($intake, function ($query) use ($intake) {
                    $query->where('intake_id', $intake);
                })
                ->when(isset($gender), function ($query) use ($gender) {
                    return $query->where('gender', $gender);
                })
                ->when($sponsor, function ($query) use ($sponsor) {
                    return $query->where('sponsor_id', $sponsor);
                })
                ->when($program, function ($query) use ($program) {
                    return $query->where('program_id', $program);
                })
                ->when($role, function ($query) use ($role) {
                    return $query->where('student_role_id', $role);
                })
                ->when($status, function ($query) use ($status) {
                    return $query->where('status', $status);
                })
                ->when(
                    $date_of_admission,
                    function ($query) use ($date_of_admission) {

                        return $query->where('date_of_admission', '>', $date_of_admission);
                    }
                )
                ->when($date_of_birth, function ($query) use ($date_of_birth) {
                    return $query->where('date_of_birth', '>', $date_of_birth);
                })
                ->get()
                ->map(fn($student) => (object) [
                    "id" => $student->id,
                    "admission_no" => $student->intake
                        ? strtoupper(
                            $student->intake->course->code . '/'
                            . str_pad($student->id, 4, '0', 0) . '/'
                            . $student->date_of_admission->format('Y')
                        )
                        : '',
                    "photo" => $student->photo ?? "",
                    "photo_url" => $student->photo ? Storage::disk('public')->url($student->photo) : asset('img/person_8x10.png'),
                    "surname" => ucfirst(Str::lower($student->surname)),
                    "first_name" => ucfirst(Str::lower($student->first_name)),
                    "middle_name" => ucfirst(Str::lower($student->middle_name)),
                    "phone" => $student->phone ?? "",
                    "email" => $student->email ?? "",
                    "box_no" => $student->box_no ?? "",
                    "post_code" => $student->post_code ?? "",
                    "town" => $student->town ?? "",
                    "physical_address" => $student->physical_address ?? "",
                    "date_of_birth" => $student->date_of_birth ? $student->date_of_birth->isoFormat("ddd, D MMM, Y") : '',
                    "age" => Carbon::parse($student->date_of_birth)->age,
                    "birth_cert_no" => $student->birth_cert_no ?? "",
                    "idno" => $student->idno ?? "",
                    "gender" => $student->gender,
                    "date_of_admission" => $student->date_of_admission ? $student->date_of_admission->isoFormat('ddd, D MMM, Y') : '',
                    "intake" => (object) [
                        "id" => $student->intake->id,
                        "name" => $student->intake->name,
                        "course" => $student->intake->course->name,
                    ],
                    "program" => (object) [
                        "id" => $student->program->id,
                        "name" => $student->program->name,
                        "description" => $student->program->description ?? "",
                    ],
                    "sponsor" => (object) [
                        "id" => $student->sponsor->id,
                        "name" => $student->sponsor->name,
                        "contact_person" => $student->sponsor->contact_person ?? "",
                        "email" => $student->sponsor->email ?? "",
                        "phone" => $student->sponsor->phone ?? "",
                        "box_no" => $student->sponsor->box_no ?? "",
                        "post_code" => $student->sponsor->post_code ?? "",
                        "town" => $student->sponsor->town ?? "",
                        "address" => $student->sponsor->address ?? "",
                    ],
                    "role" => (object) [
                        "id" => $student->intake->id,
                        "name" => $student->intake->name,
                        "description" => $student->intake->description,
                    ],
                    "status" => $student->status,
                    "plwd" => $student->plwd,
                    "plwd_details" => $student->plwd_details,
                ]);

            $data['students'] = $students;
            if ($title = request()->input('t')) {
                $data['title'] = $title;
            }
            $pdf->setOption('footer-center', 'Page [page] of [toPage]')
                ->setOption('footer-font-size', 7)
                ->loadView('pdf.students.list', $data);

            $filename = 'STUDENTS_' . date('d-m-Y') . '.pdf';
            return $pdf->stream($filename);
        }
    }
}
