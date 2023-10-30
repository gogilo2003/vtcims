<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\StaffRole;
use Validator;

class StaffRolesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getStaffRoles()
    {
        $staff_roles = StaffRole::all();
        return view('eschool::staff_roles.index', compact('staff_roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAdd()
    {
        return view('eschool::staff_roles.add');
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
            'name' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors($validator)
                ->with('global-warning', 'Some fields failed validation. Please check and try again');
        }

        $staff_role = new StaffRole;
        $staff_role->name = $request->name;
        $staff_role->description = $request->description;
        $staff_role->save();

        return redirect()
            ->route('admin-eschool-staff_roles')
            ->with('global-success', 'Staff Role added');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $validator = Validator::make([
            'id' => $id
        ], [
            'id' => 'required|exists:staff_roles'
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->with('global-warning', 'The role does not exists');
        }

        $staff_role = StaffRole::find($id);

        return view('eschool::staff_roles.show', compact('staff_role'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getEdit($id)
    {
        $validator = Validator::make([
            'id' => $id
        ], [
            'id' => 'required|exists:staff_roles'
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->with('global-warning', 'The role does not exists');
        }

        $staff_role = StaffRole::find($id);

        return view('eschool::staff_roles.edit', compact('staff_role'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function postEdit(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors($validator)
                ->with('global-warning', 'Some fields failed validation. Please check and try again');
        }

        $staff_role = new StaffRole;
        $staff_role->name = $request->name;
        $staff_role->description = $request->description;
        $staff_role->save();

        return redirect()
            ->route('admin-eschool-staff_roles')
            ->with('global-success', 'Staff Role updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $validator = Validator::make([
            'id' => $id
        ], [
            'id' => 'required|exists:staff_roles'
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->with('global-warning', 'The role does not exists');
        }

        $staff_role = StaffRole::find($id);
        $staff_role->delete();

        return redirect()
            ->route('admin-eschool-staff_roles')
            ->with('global-success', 'Role was removed');
    }
}
