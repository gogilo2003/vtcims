<?php

namespace App\Http\Controllers\V1;

use Inertia\Inertia;
use App\Models\Staff;
use App\Models\Department;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreDepartmentRequest;
use App\Http\Requests\V1\UpdateDepartmentRequest;

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
                    "name" => sprintf(
                        "%s %s",
                        Str::ucfirst(Str::lower($item->hod->first_name)),
                        Str::ucfirst(Str::lower($item->hod->surname))
                    ),
                ]
            ]);
        $instructors = Staff::whereHas('status', function ($query) {
            $query->where('name', 'like', '%current%');
        })->where('teach', 1)->get()->map(fn($item) => [
                "id" => $item->id,
                "name" => sprintf(
                    "%s %s",
                    Str::ucfirst(Str::lower($item->first_name)),
                    Str::ucfirst(Str::lower($item->surname))
                ),
            ]);
        return Inertia::render('Departments/Index', ['departments' => $departments, 'instructors' => $instructors, 'search' => $search,]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreDepartmentRequest $request)
    {

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
    public function update(UpdateDepartmentRequest $request, Department $department)
    {

        $department->code = $request->code ? $request->code : $department->code;
        $department->name = $request->name ? $request->name : $department->name;
        $department->staff_id = $request->hod ? $request->hod : $department->staff_id;
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
