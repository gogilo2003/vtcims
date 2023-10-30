<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Intake;
use App\Models\Student;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use App\Models\Department;

class ReportService
{
    public function getStudentList(Request $request, $id = null)
    {

        $pdf = App::make('snappy.pdf.wrapper')
            ->setOrientation('landscape')
            ->setPaper('A4')
            ->setOption('no-outline', true);

        if ($id) {
            $student = Student::findOrFail($id);

            $pdf->loadView('eschool::students.download.view', compact('student'));
            // return view('eschool::students.download.view',compact('student'));
            $filename = str_replace('/', '_', $student->admission_no) . '.pdf';
            return $pdf->download($filename);
        } else {
            $pdf->setOption('footer-center', 'Page [page] of [toPage]')
                ->setOption('footer-font-size', 8);
            $departments = null;

            $dpids = $request->department ? Department::whereIn('id', $request->department)->get()->pluck('id')->toArray() : null;
            $crsids = $dpids ? Course::whereIn('department_id', $dpids)->get()->pluck('id')->toArray() : null;
            $intakes = $crsids ? Intake::whereIn('course_id', $crsids)->get() : null;
            $dps = $intakes ? $intakes->pluck('id')->toArray() : null;
            $departments = $dps ? (is_array($dps) ? $dps : [$dps]) : null;
            unset($intakes);
            unset($crsids);

            $crsids = $request->course ? Course::whereIn('id', $request->course)->get()->pluck('id')->toArray() : null;
            $intakes = $crsids ? Intake::whereIn('course_id', $crsids)->get() : null;
            $crs = $intakes ? $intakes->pluck('id')->toArray() : [];
            $courses = $crs ? (is_array($crs) ? $crs : [$crs]) : null;
            unset($intakes);

            $intake = $request->intake ? $request->intake : null;
            $gender = $request->has('gender') ? $request->gender : null;
            $sponsor = $request->has('sponsor') ? $request->sponsor : null;
            $program = $request->has('program') ? $request->program : null;
            $role = $request->has('role') ? $request->role : null;
            $before_after = $request->has('before_after') ? $request->before_after : null;
            $date_of_admission = $request->has('date_of_admission') ? date_create($request->date_of_admission) : null;
            $status = $request->has('status') ? $request->status : null;

            $date_of_birth = date_create();
            if ($request->age > 0) {
                date_sub($date_of_birth, date_interval_create_from_date_string($request->age . ' years'));
            }

            $students = Student::when(
                $departments,
                function ($query) use ($departments) {
                    return $query->whereIn('intake_id', $departments);
                }
            )
                ->when($request->course, function ($query) use ($courses) {
                    return $query->whereIn('intake_id', $courses);
                })
                ->when($request->intake, function ($query) use ($intake) {
                    return $query->whereIn('intake_id', $intake);
                })
                ->when($request->has('gender'), function ($query) use ($gender) {
                    return $query->where('gender', $gender);
                })
                ->when($request->has('sponsor'), function ($query) use ($sponsor) {
                    return $query->whereIn('sponsor_id', $sponsor);
                })
                ->when($request->has('program'), function ($query) use ($program) {
                    return $query->whereIn('program_id', $program);
                })
                ->when($request->has('role'), function ($query) use ($role) {
                    return $query->whereIn('student_role_id', $role);
                })
                ->when($request->has('status'), function ($query) use ($status) {
                    return $query->where('status', $status);
                })
                ->when(
                    $request->has('before_after') && $request->has('date_of_admission'),
                    function ($query) use ($date_of_admission) {
                        return $query->where('date_of_admission', '>', $date_of_admission);
                    }
                )
                // ->when($request->age,function($query)use($date_of_birth){
                //     return $query->where('date_of_birth','>',$date_of_birth);
                // })
                ->get();

            // dd($students);

            $pdf->loadView('eschool::students.download.list', compact('students'));
            $filename = 'STUDENTS_' . date('d-m-Y') . '.pdf';
            return $pdf->download($filename);
        }
    }

    public function getEnrolmentList(Request $request)
    {
        // dd($request->all());
        $students = DB::table('students')
            ->selectRaw("DISTINCT DATE_FORMAT(date_of_admission,'%Y') as `YEAR`");

        if ($request->has('before_after') && $request->before_after && $request->has('date_of_admission') && $request->date_of_admission) {
            $students->where('date_of_admission', ($request->before_after == 'after' ? '>=' : '<='), date_create($request->date_of_admission));
        }

        $years = $students->get()->pluck('YEAR');

        $departments = DB::table('departments')
            ->select("id", "name")
            ->get();

        $enrolments = $departments->map(function ($department) use ($years) {
            $courses = DB::table('courses')
                ->where('department_id', $department->id)
                ->get();

            $intakes = DB::table('intakes')
                ->whereIn('course_id', $courses->pluck('id'))
                ->get()
                ->pluck('id');

            foreach ($years as $key => $year) {
                $male = DB::table('students')
                    ->selectRaw('count(*) AS enrolment')
                    ->whereYear('date_of_admission', $year)
                    ->where('gender', 1)
                    ->whereIn('intake_id', $intakes)
                    ->first();
                $female = DB::table('students')
                    ->selectRaw('count(*) AS enrolment')
                    ->whereYear('date_of_admission', $year)
                    ->where('gender', 0)
                    ->whereIn('intake_id', $intakes)
                    ->first();

                $department->{$year} = new class($male->enrolment, $female->enrolment)
                {
                    public $male = null;
                    public $female = null;
                    public $total = null;
                    public function __construct($male, $female)
                    {
                        $this->male = $male;
                        $this->female = $female;
                        $this->total = $male + $female;
                    }
                };
            }
            return $department;
        });

        $pdf = App::make('snappy.pdf.wrapper');
        $pdf->loadView('eschool::students.download.enrolment', compact('enrolments', 'years'))
            ->setOption('orientation', 'landscape')
            ->setOption('no-outline', true)
            ->setOption('enable-javascript', true)
            ->setOption('javascript-delay', 10000)
            ->setOption('enable-smart-shrinking', true)
            ->setOption('no-stop-slow-scripts', true);
        return $pdf->download();
        // return view('eschool::students.download.enrolment', compact('enrolments', 'years'));
    }
}
