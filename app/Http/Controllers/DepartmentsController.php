<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Department;
use App\Models\Course;

use Validator;

class DepartmentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getDepartments()
    {
        $departments = Department::all();

        return view('eschool::departments.index', compact('departments'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAdd()
    {
        return view('eschool::departments.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postAdd(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|unique:departments',
            'name' => 'required',
            'hod' => 'required|exists:staff,id'
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors($validator)
                ->with('global-warning', 'Some fields failed to validate. Please check and try again');
        }

        $department = new Department;
        $department->code = $request->code;
        $department->name = $request->name;
        $department->staff_id = $request->hod;
        $department->save();

        return redirect()
            ->back()
            ->with('global-success', 'Department added');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getView($id)
    {
        $department = Department::with('courses.intakes')->find($id);
        return view('eschool::departments.view', compact('department'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getEdit($id)
    {
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
        $validator = Validator::make($request->all(), [
            'code' => 'required|unique:departments,code,' . $request->id,
            'name' => 'required',
            'hod' => 'required|exists:staff,id'
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors($validator)
                ->with('global-warning', 'Some fields failed to validate. Please check and try again');
        }

        $department = Department::find($request->id);
        $department->code = $request->code ? $request->code : $department->code;
        $department->name = $request->name ? $request->name : $department->name;
        $department->staff_id = $request->hod ? $request->id : $department->staff_id;
        $department->save();

        return redirect()
            ->back()
            ->with('global-success', 'Department updated');
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

    public function getCourses(Request $request)
    {
        $request->validate([
            'department' => 'required|exists:departments,id'
        ]);

        $courses = Course::where('department_id', $request->department)->get();
        return response($courses)->header('Content-Type', 'application/json');
    }

    /**
     * Get Intakes related to this department
     * 
     */

    public function getIntakes(Request $request)
    {
        $request->validate([
            'department' => 'required|exists:departments,id'
        ]);

        $department = Department::find($request->department);
        $intakes = $department->intakes;
        return response($intakes)->header('Content-Type', 'application/json');
    }
}
