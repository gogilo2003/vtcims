<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Intake;
use App\Models\Course;
use App\Models\IntakeStaffSubject;
use App\Models\Subject;

class IntakesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getIntakes()
    {
        $intakes = Intake::orderBy('id', 'DESC')->get();
        return view('eschool::intakes.index', compact('intakes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postAdd(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'instructor_incharge' => 'required|exists:staff,id',
            'course' => 'required|exists:courses,id',
        ]);

        $course = Course::find($request->course);
        $dt = [];

        $name = $course->code . strtoupper(date_format(date_create($request->start_date), '/Y/M'));

        $intake = new Intake;
        $intake->start_date = $request->start_date;
        $intake->end_date = $request->end_date;
        $intake->staff_id = $request->instructor_incharge;
        $intake->course_id = $request->course;
        $intake->name = $name;
        $intake->save();

        return redirect()
            ->back()
            ->with('global-success', 'Class/Intake created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getView($id)
    {
        $intake = Intake::find($id);

        return view('eschool::intakes.view', compact('intake'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        $request->validate([
            'id' => 'required|exists:intakes',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'instructor_incharge' => 'required|exists:staff,id',
            'course' => 'required|exists:courses,id',
        ]);

        $course = Course::find($request->course);
        $dt = [];

        $name = $course->code . strtoupper(date_format(date_create($request->start_date), '/Y/M'));

        $intake = Intake::find($request->id);
        $intake->start_date = $request->start_date;
        $intake->end_date = $request->end_date;
        $intake->staff_id = $request->instructor_incharge;
        $intake->course_id = $request->course;
        $intake->name = $name;
        $intake->save();

        return redirect()
            ->back()
            ->with('global-success', 'Class/Intake updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getSubjects(Request $request)
    {
        // dd($request->intake);
        $request->validate(['intake' => 'required|array', 'intake.*' => 'exists:intakes,id']);

        $ids = IntakeStaffSubject::whereIn('intake_id', $request->intake)->select('subject_id')->distinct('subject_id')->get()->pluck('subject_id');
        $subjects = Subject::whereIn('id', $ids)->get();

        // dd($ids);

        return response($subjects)->header('Content-Type', 'application/json');
    }

    public function fetchIntakes()
    {
        $intakes = Intake::all();
        return response($intakes)->header('Content-Type', 'application/json');
    }
}
