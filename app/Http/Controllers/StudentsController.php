<?php

namespace App\Http\Controllers;

use DB;
use App;
use Img;
use Auth;

use Illuminate\Http\Request;
use App\Models\Page;
use App\Models\Term;
use App\Models\Admin;

use App\Models\Staff;
use App\Models\Course;
use App\Models\Intake;
use App\Models\Student;
use App\Models\Subject;
use App\Models\LeaveOut;
use App\Models\StaffRole;
use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Support\Facades\Validator;
use App\Services\ReportService;

/**
 *
 */
class StudentsController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth')->except('login');
    }

    public function getStudents()
    {
        $students = Student::orderBy('created_at', 'DESC')->get();
        return view('eschool::students.index', compact('students'));
    }

    public function getAdd()
    {
        return view('eschool::students.add');
    }

    public function postAdd(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'surname' => 'required',
            'first_name' => 'required',
            'email' => 'nullable|email',
            'date_of_birth' => 'required|date',
            'birth_certificate_no' => 'nullable|unique:students,birth_cert_no',
            'idno' => 'nullable|unique:students,idno',
            'gender' => 'required',
            'date_of_admission' => 'required|date',
            'intake' => 'required|exists:intakes,id',
            'program' => 'required|exists:programs,id',
            'sponsor' => 'required|exists:sponsors,id',
            'student_role' => 'required|exists:student_roles,id',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors($validator)
                ->with('global-warning', 'Some fields failed validation. Please check and try again');
        }

        $student = new Student;

        $student->surname = $request->surname;
        $student->first_name = $request->first_name;
        $student->middle_name = $request->middle_name;
        $student->phone = $request->phone ? clean_isdn($request->phone) : null;
        $student->email = $request->email;
        $student->box_no = $request->box_no;
        $student->post_code = $request->post_code;
        $student->town = $request->town;
        $student->date_of_birth = $request->date_of_birth;
        $student->birth_cert_no = $request->birth_certificate_no;
        $student->idno = $request->idno;
        $student->gender = $request->gender;
        $student->date_of_admission = $request->date_of_admission;
        $student->intake_id = $request->intake;
        $student->program_id = $request->program;
        $student->sponsor_id = $request->sponsor;
        $student->student_role_id = $request->student_role;
        $student->status = $request->status;

        $student->save();

        return redirect()
            ->back()
            ->with('global-success', 'Student added');
    }

    public function postEdit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:students',
            'surname' => 'required',
            'first_name' => 'required',
            'email' => 'nullable|email',
            'date_of_birth' => 'nullable|date',
            'birth_certificate_no' => 'nullable|unique:students,birth_cert_no,' . $request->id,
            'idno' => 'nullable|unique:students,idno,' . $request->id,
            'gender' => 'required',
            'date_of_admission' => 'required|date',
            'intake' => 'required|exists:intakes,id',
            'program' => 'required|exists:programs,id',
            'sponsor' => 'required|exists:sponsors,id',
            'student_role' => 'required|exists:student_roles,id',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors($validator)
                ->with('global-warning', 'Some fields failed validation. Please check and try again');
        }

        $student = Student::find($request->id);

        $student->surname = $request->surname;
        $student->first_name = $request->first_name;
        $student->middle_name = $request->middle_name;
        $student->phone = $request->phone ? clean_isdn($request->phone) : null;
        $student->email = $request->email;
        $student->box_no = $request->box_no;
        $student->post_code = $request->post_code;
        $student->town = $request->town;
        $student->date_of_birth = $request->date_of_birth;
        $student->birth_cert_no = $request->birth_certificate_no;
        $student->idno = $request->idno;
        $student->gender = $request->gender;
        $student->date_of_admission = $request->date_of_admission;
        $student->intake_id = $request->intake;
        $student->program_id = $request->program;
        $student->sponsor_id = $request->sponsor;
        $student->student_role_id = $request->student_role;
        $student->status = $request->status;

        $student->save();

        return redirect()
            ->back()
            ->with('global-success', 'Student updated');
    }

    public function postAttendance(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'class.*' => 'required|exists:intakes,id',
            'subject' => 'required|exists:subjects,id',
            'staff' => 'required|exists:staff,id',
            'days' => 'required',
            'days.*' => 'required',
            'term' => 'nullable|integer|exists:terms,id',
            'date' => [
                'exclude_unless:type,weekly',
                'required',
                'date',
                function ($attribute, $value, $fail) {
                    $day = (int)date_create($value)->format('w');
                    if ($day !== 1) {
                        return $fail($attribute . ' must be a monday');
                    }
                }
            ]
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors($validator)
                ->with('global-warning', 'Some fields failed validation. Please check and try again');
        }

        $pdf = App::make('snappy.pdf.wrapper');

        $intake = Intake::whereIn('id', $request->class)->orderBy('name')->get();
        $staff = Staff::find($request->staff);
        $subject = Subject::find($request->subject);
        $classes = implode(', ', $intake->pluck('name')->toArray());
        $students = Student::whereIn('intake_id', $request->class)->where('status', 'In session')->orderBy('id')->get();
        $principal = StaffRole::where('name', 'Principal')->get()->first()->staff->first();

        if ($request->type == 'weekly') {

            $days['mon'] = date_create($request->date);
            $days['tue'] = clone $days['mon'];
            $days['tue'] = date_add($days['tue'], date_interval_create_from_date_string('1 days'));
            $days['wed'] = clone $days['tue'];
            $days['wed'] = date_add($days['wed'], date_interval_create_from_date_string('1 days'));
            $days['thu'] = clone $days['wed'];
            $days['thu'] = date_add($days['thu'], date_interval_create_from_date_string('1 days'));
            $days['fri'] = clone $days['thu'];
            $days['fri'] = date_add($days['fri'], date_interval_create_from_date_string('1 days'));

            $datetonum = [
                "mon" => 1,
                "tue" => 2,
                "wed" => 3,
                "thu" => 4,
                "fri" => 5,
            ];
            foreach ($days as $key => $value) {
                if (!in_array($datetonum[$key], $request->days)) {
                    unset($days[$key]);
                }
            }

            $pdf->setOrientation('landscape')
                ->setPaper('A4')
                ->setOption('no-outline', true)
                ->setOption('footer-center', 'Page [page] of [toPage]')
                ->setOption('footer-font-size', 8);

            $pdf->loadView('eschool::students.download.attendance.' . $request->type, compact('intake', 'staff', 'subject', 'classes', 'students', 'principal', 'days'));

            $filename = strtoupper(str_studle('weekly attendance register ' . $days[array_key_first($days)]->format('j-M-Y'))) . '.pdf';
        }

        if ($request->type == 'monthly') {

            $month_start = new \DateTime(date('Y') . '-' . $request->month . '-01');
            $m = $month_start->format('Y-m');
            $t = $month_start->format('t');
            $month_end = new \DateTime($m . '-' . $t);

            $days = [];
            $week = 1;
            foreach (range(1, $t) as $d) {
                $day = new \DateTime($m . "-$d");

                if ($day->format('N') < 6 && in_array($day->format('N'), $request->days)) {
                    $days['Week ' . $week][] = $day;
                }
                if ($day->format('N') == 7) {
                    $week++;
                }
            }

            $pdf->setOrientation('landscape')
                ->setPaper('A4')
                ->setOption('no-outline', true)
                ->setOption('footer-center', 'Page [page] of [toPage]')
                ->setOption('footer-font-size', 8);

            // return view('eschool::students.download.attendance.' . $request->type, compact('intake', 'staff', 'subject', 'classes', 'students', 'principal','month_start','month_end','days'));
            $pdf->loadView('eschool::students.download.attendance.' . $request->type, compact('intake', 'staff', 'subject', 'classes', 'students', 'principal', 'month_start', 'month_end', 'days'));

            $filename = strtoupper(str_studle('monthly attendance register ' . $month_start->format('j-M-Y'))) . '.pdf';
        }
        if ($request->type == 'termly') {

            $term = Term::find($request->term);
            $term_start = new \DateTime($term->start_date);
            $m = $term_start->format('Y-m');
            $t = $term_start->format('t');
            $term_end = new \DateTime($term->end_date);

            $days = [];
            $week = 1;
            $interval = date_interval_create_from_date_string('1 day');
            for ($day = $term_start; $day < $term_end; $day->add($interval)) {

                if ($day->format('N') < 6 && in_array($day->format('N'), $request->days)) {
                    $days['WK ' . $week][] = clone $day;
                }
                if ($day->format('N') == 7) {
                    $week++;
                }
            }
            // dd($request->days);
            $pdf->setOrientation('landscape')
                ->setPaper('A4')
                ->setOption('no-outline', true)
                ->setOption('footer-center', 'Page [page] of [toPage]')
                ->setOption('footer-font-size', 8);

            // return view('eschool::students.download.attendance.' . $request->type, compact('intake', 'staff', 'subject', 'classes', 'students', 'principal','term_start','term_end','days'));
            $pdf->loadView('eschool::students.download.attendance.' . $request->type, compact('intake', 'staff', 'subject', 'classes', 'students', 'principal', 'term_start', 'term_end', 'days'));

            $filename = strtoupper(str_studle('termly attendance register ' . $term_start->format('j-M-Y'))) . '.pdf';
        }

        return $pdf->download($filename);
    }

    /**
     * Process uploaded sudent photo
     */
    public function postPhoto(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:students,id',
            //'photo' => 'required|image|dimensions:ratio=4/5',
            'photo' => 'required|file|image',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->with('global-warning', 'Something went wrong while you were uploading the photo. Please check and try again');
        }



        if ($request->hasFile('photo')) {
            $file = $request->photo;

            if ($file->isValid()) {
                $student = Student::find($request->id);

                $dir = public_path('images/students/');

                if (!file_exists($dir)) {
                    mkdir($dir, 0755, TRUE);
                }

                $filename = time() . '.' . $file->guessClientExtension();

                if (file_exists($dir . $student->photo) && $student->photo) {
                    unlink($dir . $student->photo);
                }

                $image = Img::make($file->getRealPath());
                $size = $image->width() >= $image->height() ? $image->height() : $image->width();
                $image->fit($size, $size);
                $image->save($dir . $filename);
                $image->destroy();

                $student->photo = $filename;

                $student->save();

                return redirect()
                    ->back()
                    ->with('global-success', 'Picture uploaded');
            }
        }

        return redirect()
            ->back()
            ->with('global-danger', 'Your photo could not be uploaded. Please try again');
    }

    public function postMassRegister(Request $request)
    {
        return redirect()
            ->back()
            ->with('global-success');
    }

    public function getView($id)
    {
        $student = Student::with('role')->find($id);
        return view('eschool::students.view', compact('student'));
    }

    public function getDownload(Request $request, ReportService $report, $id = null)
    {
        // dd($request->all());
        return $report->getStudentList($request, $id);
    }

    /**
     * Get sumarised current totals
     *
     */
    public function postTotals(Request $request)
    {
        $male = Student::selectRaw('count(*) as count')->where('status', 'In session')->where('gender', 0)->first();
        $female = Student::selectRaw('count(*) as count')->where('status', 'In session')->where('gender', 1)->first();
        // dd($male);
        $data = [
            'male' => $male->count,
            'female' => $female->count,
            'total' => $male->count + $female->count
        ];
        return response($data)->header('Content-Type', 'application/json');
    }

    /**
     * Get curent enrolment
     */
    public function currentEnrolment(Request $request)
    {
        $labels = ['Male', 'Female'];

        $male = Student::selectRaw('count(*) as count')->where('status', 'In session')->where('gender', 0)->first();
        $female = Student::selectRaw('count(*) as count')->where('status', 'In session')->where('gender', 1)->first();
        // dd($male);
        $data = [$male->count, $female->count];
        return response(compact('labels', 'data'))->header('Content-Type', 'application/json');
    }


    /**
     * Get curent enrolment status
     */
    public function overalEnrolmentStatus(Request $request)
    {
        $labels = ['In session', 'On Attachment', 'Completed', 'Dropout'];
        $data = [];

        foreach ($labels as $key => $value) {
            $data[] = Student::selectRaw('count(*) as count')->where('status', $value)->first()->count;
        }

        return response(compact('labels', 'data'))->header('Content-Type', 'application/json');
    }

    /**
     * Yearly Enrollment male female and total
     *
     */
    public function yearlyEnrolment(Request $request)
    {
        $dates = DB::table('students')->select(DB::raw('min(date_of_admission) as start, max(date_of_admission) as end'))->get()->first();

        $weekly = DB::table('hits')
            ->where('url', 'NOT LIKE', "%admin%")
            ->where('url', 'NOT LIKE', "%api%")
            ->where('url', 'NOT LIKE', "%public%")
            ->get();

        $begin = date_create($dates->start);
        $end = date_create($dates->end);

        $interval = new \DateInterval('P1Y');
        $daterange = new \DatePeriod($begin, $interval, $end);
        $labels = [];

        // rsort($daterange);

        foreach ($daterange as $key => $date) {
            $labels[] = $date->format("Y");
            // if ($key===2)
            //     break;
        }

        $datasets = [];
        $genders = ["Male", "Female"];
        $colors = ['orange', 'purple', 'yellow', 'beige', 'red'];

        $labels = array_slice($labels, -5);

        foreach ($genders as $key => $gender) {
            $data = [];
            foreach ($labels as $date) {
                $students = DB::table('students')
                    ->select(DB::raw('count(*) as enrolment'))
                    ->where('gender', $key)
                    ->whereYear('date_of_admission', $date)
                    ->first();

                $data[] = $students->enrolment;
            }

            $data[] = 0;
            $datasets[] = [
                'label' => $gender,
                'data' => $data,
                'backgroundColor' => $colors[$key],
                'borderColor' => $colors[$key],
                // 'pointBackgroundColor'=>'#fff',
                // 'pointBorderColor'=>'#fff'
            ];
        }
        return response(compact('labels', 'datasets'))->header('Content-Type', 'application/json');
    }

    public function postEnrolment(Request $request, ReportService $reportService)
    {
        return $reportService->getEnrolmentList($request);
    }

    public function postLeaveOut(Request $request, $id = null)
    {
        $leaveout = null;

        if ($id) {
            $leaveout = LeaveOut::with('staff', 'student')->find($id);
        } else {
            $validator = Validator::make($request->all(), [
                'valid_until' => 'required|date|after_or_equal:now',
                'id' => 'required|exists:students',
                'reasons' => 'required'
            ]);

            if ($validator->fails()) {
                return redirect()
                    ->back()
                    ->withErrors($validator)
                    ->withInput()
                    ->with('global-warning', 'Some fields failed to validate. Please check and try again');
            }
            $leaveout = new LeaveOut;
            $admin = Admin::with('staff')->find(Auth::guard('admin')->user()->id);
            // dd($admin);
            $leaveout->staff_id = $admin->staff->id;
            $leaveout->student_id = $request->id;
            $leaveout->valid_until = $request->valid_until;
            $leaveout->reasons = $request->reasons;
            $leaveout->remarks = $request->remarks;
            $leaveout->save();

            $leaveout = LeaveOut::with('student', 'staff')->find($leaveout->id);
        }

        $pdf = App::make('snappy.pdf.wrapper')
            ->loadView('eschool::students.download.leaveout', compact('leaveout'))
            ->setPaper('A4')
            ->setOption('no-outline', true);
        // return view('eschool::students.download.leaveout',compact('leaveout'));
        return $pdf->download('leaveout.pdf');
    }
}
