<?php

namespace App\Http\Controllers\V1;

use Inertia\Inertia;
use App\Models\Intake;
use App\Models\Program;
use App\Models\Sponsor;
use App\Models\Student;
use App\Models\StudentRole;
use Illuminate\Support\Carbon;
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
        ])->orderBy('id', 'DESC')->when(
                $search,
                function ($query) use ($search) {
                    $query->where('surname', 'LIKE', '%' . $search . '%')
                        ->orWhere('middle_name', 'LIKE', '%' . $search . '%')
                        ->orWhere('first_name', 'LIKE', '%' . $search . '%')
                        ->orWhere('id', 'LIKE', '%' . $search . '%')
                        ->orWhere('id', $search);

                    $ar = explode(' ', $search);

                    if (count($ar) > 1) {
                        $query->orWhere(function ($query) use ($ar) {
                            if (isset ($ar[0])) {
                                $query->where('surname', 'LIKE', $ar[0] . '%');
                            }
                            if (isset ($ar[1])) {
                                $query->where('first_name', 'LIKE', $ar[1] . '%');
                            }
                            if (isset ($ar[2])) {
                                $query->where('middle_name', 'LIKE', $ar[2] . '%');
                            }
                        });
                    }
                }
            )->paginate(10)->through(
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
                    $student->examinations = $examinations;
                    return $student;
                }
            );

        $intakes = Intake::orderBy('name', 'DESC')->get()->map(fn($item) => [
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
        $student_roles = StudentRole::orderBy('name', 'DESC')->get()->map(fn($item) => [
            "id" => $item->id,
            "name" => $item->name
        ]);

        return Inertia::render('Students/Index', [
            'students' => $students,
            'intakes' => $intakes,
            'programs' => $programs,
            'sponsors' => $sponsors,
            'student_roles' => $student_roles,
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
        $student->student_role_id = $request->student_role;
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
        $student->student_role_id = $request->student_role;
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
}
