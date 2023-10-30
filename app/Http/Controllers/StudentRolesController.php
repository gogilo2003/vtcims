<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\StudentRole;

class StudentRolesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getStudentRoles()
    {
        $student_roles = StudentRole::all();
        return view('eschool::student_roles.index', compact('student_roles'));
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
            'name' => 'required|unique:student_roles,name',
        ]);

        $student_role = new StudentRole;

        $student_role->name = $request->name;
        $student_role->description = $request->description;

        $student_role->save();

        return redirect()
            ->back()
            ->with('global-success', 'Student Role added');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
            'id' => 'required|exists:student_roles',
            'name' => 'required|unique:student_roles,name,' . $request->id,
        ]);

        $student_role = StudentRole::find($request->id);

        $student_role->name = $request->name;
        $student_role->description = $request->description;

        $student_role->save();

        return redirect()
            ->back()
            ->with('global-success', 'Student Role updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function postDelete(Request $request)
    {
        $request->validate(['id' => 'required|exists:student_roles']);

        $student_role = StudentRole::find($request->id);
        $student_role->delete();

        return redirect()
            ->back()
            ->with('global-success', 'StudentRole deleted');
    }
}
