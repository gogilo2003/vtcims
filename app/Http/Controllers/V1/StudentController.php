<?php

namespace App\Http\Controllers\V1;

use App\Models\Guardian;
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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\V1\StoreStudentRequest;
use App\Http\Requests\V1\UpdateStudentRequest;
use App\Http\Requests\V1\UploadPictureRequest;
use App\Support\StudentUtil;

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
                    $query->orWhere(function ($query) use ($search) {
                        $query->whereHas('intake.course.department', function ($query) use ($search) {
                            $query->where('name', 'like', '%' . $search . '%');
                        })->orWhereHas('intake.course', function ($query) use ($search) {
                            $query->where('name', 'like', '%' . $search . '%');
                        })->orWhereHas('intake', function ($query) use ($search) {
                            $query->where('name', 'like', '%' . $search . '%');
                        });
                    });
                }
            )->paginate(8)->through(
                function ($student) {
                    $id = $student->id;
                    // dd(StudentUtil::generateFeeSummary($id));
                    return (object) [
                        "id" => $student->id,
                        "admission_no" => StudentUtil::prepAdmissionNo($student),
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
                        "age" => $student->date_of_birth ? $student->date_of_birth->age : '',
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
                            "id" => $student->role->id,
                            "name" => $student->role->name,
                            "description" => $student->role->description,
                        ],
                        "status" => $student->status,
                        "guardian" => $student->guardian ? [
                            "id" => $student->guardian->id,
                            "name" => $student->guardian->name,
                            "phone" => $student->guardian->phone,
                            "email" => $student->guardian->email,
                        ] : null,
                        "plwd" => $student->plwd,
                        "plwd_details" => $student->plwd_details,
                        "examinations" => StudentUtil::generateExamSummary($id),
                        "fees" => StudentUtil::generateFeeSummary($id),
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
        $years = DB::table('students')
            ->selectRaw("DISTINCT DATE_FORMAT(date_of_admission,'%Y') as `YEAR`")
            ->orderBy('YEAR', 'DESC')->get()->pluck('YEAR');

        return Inertia::render('Students/Index', [
            'students' => $students,
            'departments' => $departments,
            'courses' => $courses,
            'intakes' => $intakes,
            'programs' => $programs,
            'sponsors' => $sponsors,
            'roles' => $roles,
            'years' => $years,
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

        $guardian = new Guardian();
        $guardian->name = $request->guardian_name;
        $guardian->phone = $request->guardian_phone;
        $guardian->email = $request->guardian_email;
        $guardian->save();
        $guardian->students()->save($student);
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

        $guardian = $student->guardian;
        $guardian->name = $request->guardian_name;
        $guardian->phone = $request->guardian_phone;
        $guardian->email = $request->guardian_email;
        $guardian->save();

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
            ->setPaper('A4')
            ->setOption('no-outline', true);

        if ($id) {
            $student = Student::findOrFail($id);
            $examSummary = StudentUtil::generateExamSummary($id);
            $feeSummary = StudentUtil::generateFeeSummary($id);

            $pdf->setOrientation('portrait');

            $viewName = 'pdf.students.view';
            if (file_exists(resource_path('views/pdf/custom/students/view'))) {
                $viewName = 'pdf.custom.students.view';
            }

            $pdf->loadView($viewName, [
                'student' => $this->mapStudent($student, true),
                "examination" => $examSummary,
                "fees" => $feeSummary,
            ]);

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
            $year = request()->has('y') ? request()->input('y') : null;

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
                ->when($year, function ($query) use ($year) {
                    return $query->whereYear('date_of_admission', $year);
                })
                ->get()
                ->map(fn(Student $student) => $this->mapStudent($student, false));

            $data['students'] = $students;
            if ($title = request()->input('t')) {
                $data['title'] = $title;
            }

            $viewName = 'pdf.students.list';
            if (file_exists(resource_path('views/pdf/custom/students/list'))) {
                $viewName = 'pdf.custom.students.list';
            }

            $pdf->setOrientation('landscape')
                ->setOption('footer-center', 'Page [page] of [toPage]')
                ->setOption('footer-font-size', 7)
                ->loadView($vieName, $data);

            $filename = 'STUDENTS_' . date('d-m-Y') . '.pdf';
            return $pdf->download($filename);
        }
    }

    function enrollment()
    {
        $years = DB::table('students')
            ->selectRaw("DISTINCT DATE_FORMAT(date_of_admission,'%Y') as `YEAR`")
            ->orderBy('YEAR', 'ASC')->get()->pluck('YEAR');

        if (request()->input('y')) {
            $years = collect(explode(",", request()->input('y')))->sort();
        }

        $departments = Department::select("id", "name")
            ->get();

        $enrollments = $departments->map(
            function ($department) use ($years) {
                $data = [
                    "id" => $department->id,
                    "name" => $department->name,
                ];
                foreach ($years as $year) {

                    $male = Student::whereYear('date_of_admission', $year)
                        ->where('gender', 0)
                        ->whereHas('intake.course', function ($query) use ($department) {
                            $query->where('department_id', $department->id);
                        })->get()->count();

                    $female = Student::whereYear('date_of_admission', $year)
                        ->where('gender', 1)
                        ->whereHas('intake.course', function ($query) use ($department) {
                            $query->where('department_id', $department->id);
                        })->get()->count();

                    $plwd = Student::whereYear('date_of_admission', $year)
                        ->where('plwd', 1)
                        ->whereHas('intake.course', function ($query) use ($department) {
                            $query->where('department_id', $department->id);
                        })->get()->count();

                    $data[$year] = (object) [
                        "male" => $male,
                        "female" => $female,
                        "total" => $male + $female,
                        "plwd" => $plwd,
                    ];
                }

                return (object) $data;
            }
        );

        $pdf = App::make('snappy.pdf.wrapper');

        $viewName = 'pdf.students.enrollment';
        if (file_exists(resource_path('views/pdf/custom/students/enrollment'))) {
            $viewName = 'pdf.custom.students.enrollment';
        }

        $pdf->loadView($viewName, compact('enrollments', 'years'))
            ->setOption('orientation', 'landscape')
            ->setOption('no-outline', true)
            ->setOption('enable-javascript', true)
            ->setOption('javascript-delay', 10000)
            ->setOption('enable-smart-shrinking', true)
            ->setOption('no-stop-slow-scripts', true);
        return $pdf->download();
    }
    protected function mapStudent(Student $student, $base64 = false)
    {
        return (object) [
            "id" => $student->id,
            "admission_no" => StudentUtil::prepAdmissionNo($student),
            "photo" => $student->photo ?? "",
            "photo_url" => StudentUtil::getPhotoUrl($student->photo, $base64),
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
                "id" => $student->role->id,
                "name" => $student->role->name,
                "description" => $student->role->description,
            ],
            "status" => $student->status,
            "plwd" => $student->plwd,
            "plwd_details" => $student->plwd_details,
        ];
    }
}
