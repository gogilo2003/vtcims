<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;

use App\Models\Examination;
use App\Models\Result;
use App\Models\Term;
use App\Models\IntakeStaffSubject;

use Validator;
use App;

class ExaminationsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getExaminations()
    {
        $examinations = Examination::orderBy('id', 'DESC')->get();
        return view('eschool::examinations.index', compact('examinations'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postAdd(Request $request)
    {
        // dd($request->all());

        $validator = Validator::make($request->all(), [
            'title' => 'required|unique:examinations,title',
            'intakes' => 'required',
            'intakes.*' => 'exists:intakes,id',
            'term' => 'required|exists:terms,id',
            'subject' => 'required|exists:subjects,id'
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors($validator)
                ->with('global-warning', 'Some fields failed validation. Please check and try again');
        }

        $examination = new Examination;

        $examination->title = $request->title;
        $examination->subject_id = $request->subject;
        $examination->term_id = $request->term;
        $examination->notes = $request->notes;

        $examination->save();

        $examination->intakes()->sync($request->intakes);

        return redirect()
            ->back()
            ->with('global-success', 'Examination added');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function postEdit(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'title' => [
                'required',
                Rule::unique('examinations', 'title')->ignore($request->id)
            ],
            'intakes' => 'required',
            'intakes.*' => 'exists:intakes,id',
            'subject' => 'required|exists:subjects,id'
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors($validator)
                ->with('global-warning', 'Some fields failed validation. Please check and try again');
        }

        $examination = Examination::find($request->id);

        $examination->title = $request->title;
        $examination->subject_id = $request->subject;
        $examination->term_id = $request->term;
        $examination->notes = $request->notes;

        $examination->save();

        $examination->intakes()->sync($request->intakes);

        return redirect()
            ->back()
            ->with('global-success', 'Examination updated');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getView($id)
    {
        $examination = Examination::with('tests')->find($id);
        return view('eschool::examinations.view', compact('examination'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getMarks($id)
    {
        $examination = Examination::with('tests')->find($id);
        return view('eschool::examinations.marks', compact('examination'));
    }

    /**
     * Post marks for tests.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function postMarks(Request $request)
    {
        // dd($request->all());
        if ($request->has('marks')) {
            foreach ($request->marks as $student => $tests) {
                foreach ($tests as $test => $score) {
                    if ($score) {
                        $result = new Result;
                        $result->test_id = $test;
                        $result->score = $score;
                        $result->student_id = $student;
                        $result->save();
                    }
                }
            }
        }

        if ($request->has('edited_marks')) {
            foreach ($request->edited_marks as $result => $score) {
                if ($score) {
                    $result = Result::find($result);
                    $result->score = $score;
                    $result->save();
                }
            }
        }


        return redirect()
            ->route('admin-eschool-examinations-view', $request->examination)
            ->with('global-success', 'Results have been saved');
    }

    /**
     * Download examination marklist
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getDownload($id)
    {
        $pdf = App::make('snappy.pdf.wrapper');
        $examination = Examination::with('tests')->find($id);
        $intakes = implode(', ', $examination->intakes->pluck('name')->toArray());
        $pdf->loadView('eschool::examinations.download.examination', compact('examination', 'intakes'))
            ->setPaper('A4')
            ->setOption('no-outline', true)
            ->setOption('footer-center', 'Page [page] of [toPage]')
            ->setOption('footer-font-size', 8);

        $filename = explode('-', $examination->title);
        foreach ($filename as $key => $value) {
            $filename[$key] = trim($value);
        }
        $filename = strtoupper(str_replace('-', '_', str_replace(' ', '-', implode('-', $filename)))) . '.pdf';

        return $pdf->download($filename);
    }

    public function postMarklist(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            "department" => "required|integer|exists:departments,id",
            "course" => "required|integer|exists:courses,id",
            'intake.*' => 'required|integer|exists:intakes,id',
            'subject.*' => 'required|integer|exists:subjects,id'
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors($validator)
                ->with('global-warning', 'Some fields failed validation. Please check and try again');
        }

        $pdf = App::make('snappy.pdf.wrapper')
            ->setOrientation('portrait')
            ->setPaper('A4')
            ->setOption('no-outline', true);

        $pdf->setOption('footer-center', 'Page [page] of [toPage]')
            ->setOption('footer-font-size', 8);
        $departments = null;

        $dpids = $request->department ? Department::where('id', $request->department)->get()->pluck('id')->toArray() : null;
        $crsids = $dpids ? Course::where('department_id', $dpids)->get()->pluck('id')->toArray() : null;
        $intakes = $crsids ? Intake::whereIn('course_id', $crsids)->get() : null;
        $dps = $intakes ? $intakes->pluck('id')->toArray() : null;
        $departments = $dps ? (is_array($dps) ? $dps : [$dps]) : null;
        unset($intakes);
        unset($crsids);

        $crsids = $request->course ? Course::where('id', $request->course)->get()->pluck('id')->toArray() : null;
        $intakes = $crsids ? Intake::whereIn('course_id', $crsids)->get() : null;
        $crs = $intakes ? $intakes->pluck('id')->toArray() : [];
        $courses = $crs ? (is_array($crs) ? $crs : [$crs]) : null;
        unset($intakes);

        $intake = $request->intake ? $request->intake : null;

        $date_of_birth = date_create();
        if ($request->age > 0) {
            date_sub($date_of_birth, date_interval_create_from_date_string($request->age . ' years'));
        }

        $before_after = $request->before_after;
        $date_of_admission = $request->date_of_admission;

        $students = Student::where('status', 'In session')
            ->when($departments, function ($query) use ($departments) {
                return $query->whereIn('intake_id', $departments);
            })
            ->when($request->course, function ($query) use ($courses) {
                return $query->whereIn('intake_id', $courses);
            })
            ->when($request->intake, function ($query) use ($intake) {
                return $query->whereIn('intake_id', $intake);
            })
            ->get();

        $intakes = implode(" | ", Intake::whereIn('id', $request->intake)->get()->pluck('name')->toArray());
        $subject = Subject::find($request->subject);
        $columns = explode(",", $request->columns);

        // dd($intake);

        $pdf->loadView('examinations.download.marklist', compact('students', 'intakes', 'subject', 'columns'));
        $filename = 'MARKLIST_' . date('d-m-Y') . '.pdf';
        return $pdf->download($filename);
    }
}
