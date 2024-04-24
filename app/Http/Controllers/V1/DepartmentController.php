<?php

namespace App\Http\Controllers\V1;

use Inertia\Inertia;
use App\Models\Staff;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        $search = request()->input('search');

        $departments = Department::when($search, function ($query) use ($search) {
            $query->where('name', 'LIKE', '%' . $search . '%');
        })->with('hod')->paginate(10)->through(fn($item) => [
                "id" => $item->id,
                "code" => $item->code,
                "name" => $item->name,
                "hod" => [
                    "id" => $item->hod->id,
                    "name" => trim(sprintf('%s %s %s', $item->hod->surname, $item->hod->first_name, $item->hod->middle_name)),
                ]
            ]);
        $instructors = Staff::whereHas('status', function ($query) {
            $query->where('name', 'current');
        })->where('teach', 1)->get()->map(fn($item) => [
                "id" => $item->id,
                "name" => sprintf("%s %s %s", $item->surname, $item->last_name, $item->middle_name),
            ]);
        return Inertia::render('Departments/Index', ['departments' => $departments, 'instructors' => $instructors, 'search' => $search,]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
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
                ->with('warning', 'Some fields failed to validate. Please check and try again');
        }

        $department = new Department;
        $department->code = $request->code;
        $department->name = $request->name;
        $department->staff_id = $request->hod;
        $department->save();

        return redirect()
            ->back()
            ->with('success', 'Department added');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Department $department
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Department $department)
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
                ->with('warning', 'Some fields failed to validate. Please check and try again');
        }

        $department = Department::find($request->id);
        $department->code = $request->code ? $request->code : $department->code;
        $department->name = $request->name ? $request->name : $department->name;
        $department->staff_id = $request->hod ? $request->id : $department->staff_id;
        $department->save();

        return redirect()
            ->back()
            ->with('success', 'Department updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Department $department
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Department $department)
    {
        $department->delete();
        return redirect()
            ->back()
            ->with('success', 'Department deleted');
    }
}
