<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreStudentRoleRequest;
use App\Http\Requests\V1\UpdateStudentRoleRequest;
use App\Models\StudentRole;
use Inertia\Inertia;

class StudentRoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $search = request()->input('search');
        $roles = StudentRole::when($search, function ($query) use ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        })->paginate(8)->through(fn(StudentRole $role) => [
                "id" => $role->id,
                "name" => $role->name,
                "description" => $role->description,
            ]);
        return Inertia::render('Students/Roles', ['roles' => $roles, 'search' => $search]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStudentRoleRequest $request)
    {
        $studentRole = new StudentRole();
        $studentRole->name = $request->name;
        $studentRole->description = $request->description;
        $studentRole->save();

        return redirect()->back()->with('success', 'Student Role created');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStudentRoleRequest $request, StudentRole $studentRole)
    {
        $studentRole->name = $request->name;
        $studentRole->description = $request->description;
        $studentRole->save();

        return redirect()->back()->with('success', 'Student Role updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StudentRole $studentRole)
    {
        $studentRole->delete();

        return redirect()->back()->with('success', 'Student Role deleted');
    }
}
