<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Course;
use App\Models\Intake;

class CoursesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getCourses()
    {
        $courses = Course::all();
        return view('eschool::courses.index', compact('courses'));
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
            'department' => 'required|exists:departments,id',
            'staff' => 'required|exists:staff,id',
            'code' => 'required|unique:courses|max:5',
            'name' => 'required'
        ]);

        $course = new Course;
        $course->department_id = $request->department;
        $course->code = $request->code;
        $course->name = $request->name;
        $course->staff_id = $request->staff;
        $course->save();

        return redirect()
            ->back()
            ->with('global-success', 'Course added');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getView($id)
    {
        $course = Course::find($id);

        return view('eschool::courses.view', compact('course'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getEdit($id)
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
            'id' => 'required|exists:courses,id',
            'department' => 'required|exists:departments,id',
            'staff' => 'required|exists:staff,id',
            'code' => 'required|unique:courses,code,' . $request->id . '|max:5',
            'name' => 'required'
        ]);

        $course = Course::find($request->id);
        $course->department_id = $request->department;
        $course->code = $request->code;
        $course->name = $request->name;
        $course->staff_id = $request->staff;
        $course->save();

        return redirect()
            ->back()
            ->with('global-success', 'Course updated');
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
     * Get Courses related to this department
     *
     */
    public function getIntakes(Request $request)
    {
        $request->validate([
            'course' => 'required|exists:courses,id'
        ]);

        $intakes = Intake::where('course_id', $request->course)->get();
        return response($intakes)->header('Content-Type', 'application/json');
    }
}
