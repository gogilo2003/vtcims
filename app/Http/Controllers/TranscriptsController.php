<?php

namespace App\Http\Controllers;

use App;
use Validator;
use Illuminate\Http\Request;

use App\Models\Term;
use App\Models\Test;
use Illuminate\Validation\Rule;
use App\Models\Staff;
use App\Models\Intake;

use App\Models\Student;
use App\Http\Controllers\Controller;
use App\Models\Examination;

class TranscriptsController extends Controller
{

    public function getTranscripts()
    {
        return view('eschool::examinations.transcripts.show');
    }

    public function postTranscripts(Request $request)
    {

        // dump($request->all());
        $validator = Validator::make($request->all(), [
            'admission_no' => 'nullable|exists:students,id',
            'term' => 'required|exists:terms,id',
            'intake' => 'required_without:admission_no|exists:intakes,id'
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors($validator)
                ->with('global-warning', '<h4>Some fields failed validation. Please check and try again</h4>' . make_html_list($validator->errors()->all()));
        }

        $principal = Staff::where('staff_role_id', 1)->orderBy('id', 'desc')->first();

        $term = Term::find($request->term);

        $student = $request->has('admission_no') ? Student::with(['intake.examinations' => function ($query) use ($term) {
            return $query->where('examinations.term_id', $term->id);
        }])->find($request->admission_no) : null;

        $intake = $request->has('intake') ? Intake::with(['examinations' => function ($query) use ($term) {
            return $query->where('term_id', $term->id);
        }])->find($request->intake) : null;

        if ($request->has('download')) {
            if ($student) {
                $name = strtoupper(str_replace(' ', '-', str_replace('-', '.', str_replace('/', '.', sprintf("Transacript %s %s", str_replace(' ', '', $student->admission_no), str_replace(' ', '.', str_replace(' - ', '.', $term->year_name))))))) . '.pdf';
                $pdf = App::make('snappy.pdf.wrapper')
                    // ->setOrientation('landscape')
                    ->setPaper('A4')
                    ->setOption('no-outline', true)
                    ->loadView('eschool::examinations.transcripts.download', compact('student', 'term', 'principal'));

                return $pdf->download($name);
            }

            $name = strtoupper(str_replace(' ', '-', str_replace('-', '.', str_replace('/', '.', sprintf("Transcripts %s %s", str_replace(' ', '', $intake->name), str_replace(' ', '.', str_replace(' - ', '.', $term->year_name))))))) . '.pdf';

            $pdf = App::make('snappy.pdf.wrapper')
                // ->setOrientation('landscape')
                ->setPaper('A4')
                ->setOption('no-outline', true)
                ->loadView('eschool::examinations.transcripts.download', compact('intake', 'term', 'principal'));

            return $pdf->download($name);
        }
        if ($student) {
            return view('eschool::examinations.transcripts.show', compact('student', 'term', 'principal'));
        }

        return view('eschool::examinations.transcripts.show', compact('intake', 'term', 'principal'));
    }

    public function getMarklists(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'intake' => 'nullable|exists:intakes,id',
            'term' => 'nullable|exists:terms,id',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors($validator)
                ->with('global-warning', 'Some fields failed validtion. Please check and try again');
        }

        $principal = Staff::where('staff_role_id', 1)->orderBy('id', 'desc')->first();
        $term = $request->has('term') ? Term::find($request->term) : Term::first();

        $intake = $request->has('intake') ? Intake::with(['examinations' => function ($query) use ($term) {
            return $query->where('term_id', $term->id);
        }])->find($request->intake) : Intake::orderBy('start_date', 'DESC')->first();

        if ($request->has('download')) {

            $name = strtoupper(str_replace(' ', '-', str_replace('-', '.', str_replace('/', '.', sprintf("Marklist %s %s", str_replace(' ', '', $intake->name), str_replace(' ', '.', str_replace(' - ', '.', $term->year_name))))))) . '.pdf';

            $pdf = App::make('snappy.pdf.wrapper')
                ->setOrientation('landscape')
                ->setPaper('A4')
                ->setOption('no-outline', true)
                ->loadView('eschool::examinations.marklists.download', compact('intake', 'term', 'principal'));

            return $pdf->download($name);
        }
        return view('eschool::examinations.marklists.show', compact('intake', 'term', 'principal'));
    }
}
