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
     */
    public function index()
    {
        $search = request()->input('search');

        $departments = Department::when($search, function ($query) use ($search) {
            $query->where('name', 'LIKE', '%' . $search . '%');
        })->with('hod')->paginate(10)->through(fn ($item) => [
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
        })->where('teach', 1)->get()->map(fn ($item) => [
            "id" => $item->id,
            "name" => sprintf("%s %s %s", $item->surname, $item->last_name, $item->middle_name),
        ]);
        return Inertia::render('Departments/Index', ['departments' => $departments, 'instructors' => $instructors, 'search' => $search,]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Department $department)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Department $department)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Department $department)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Department $department)
    {
        //
    }
}
