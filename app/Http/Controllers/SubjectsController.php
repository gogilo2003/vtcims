<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;

use App\Models\Subject;
use App\Models\Course;
use App\Models\IntakeStaffSubject;

class SubjectsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getSubjects()
    {
        $subjects = Subject::all();
        return view('eschool::subjects.index', compact('subjects'));
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
        $request->validate([
            'subject_code' => 'required|unique:subjects,code',
            'subject_name' => 'required',
            'courses.*' => 'nullable|exists:courses,id',
        ]);

        $subject = new Subject;

        $subject->name = $request->subject_name;
        $subject->code = $request->subject_code;

        $subject->save();

        $subject->courses()->attach($request->courses);
        $subject->staff()->attach($request->staff);

        return redirect()
            ->back()
            ->with('global-success', 'Subject added');
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getView($id)
    {

        $subject = Subject::find($id);

        return view('eschool::subjects.view', compact('subject'));
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
        $request->validate([
            'subject_code' => 'required|unique:subjects,code,' . $request->id,
            'subject_name' => 'required',
            'courses.*' => 'nullable|exists:courses,id',
        ]);

        $subject = Subject::find($request->id);

        $subject->name = $request->subject_name;
        $subject->code = $request->subject_code;

        $subject->save();

        $subject->courses()->sync($request->courses);
        $subject->staff()->sync($request->staff);

        return redirect()
            ->back()
            ->with('global-success', 'Subject updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function postDelete(Request $request)
    {
        $request->validate(['id' => 'required|exists:subjects']);

        $subject = Subject::find($request->id);
        $subject->courses()->detach($subject->course_ids);
        $subject->staff()->detach($subject->staff_ids);
        $subject->delete();

        return redirect()
            ->back()
            ->with('global-success', 'Subject deleted');
    }

    public function getAllocate()
    {
        $allocations = IntakeStaffSubject::with('subject', 'staff')->orderBy('created_at', 'DESC')->get();
        return view('eschool::subjects.allocate', compact('allocations'));
    }

    public function postAllocate(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'class' => 'required|exists:intakes,id',
            'teacher' => 'required|exists:staff,id',
            'subject' => [
                'required', 'exists:subjects,id',
                Rule::unique('intake_staff_subject', 'subject_id')->where(function ($query) use ($request) {
                    return $query->where('intake_id', $request->class);
                })->ignore($request->id, 'id')
            ],
        ]);

        $allocation = ($request->has('id') && $request->id) ? IntakeStaffSubject::find($request->id) : new IntakeStaffSubject;

        $allocation->subject_id = $request->subject;
        $allocation->staff_id = $request->teacher;
        $allocation->intake_id = $request->class;

        $allocation->save();

        return redirect()
            ->back()
            ->with('global-success', 'Subject Allocation saved');
    }

    public function fetchSubjects()
    {
        $subjects = Subject::all();
        return response($subjects)->header('Content-Type', 'application/json');
    }
}
