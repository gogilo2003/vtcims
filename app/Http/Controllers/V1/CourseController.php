<?php

namespace App\Http\Controllers\V1;

use App\Models\Staff;
use App\Models\Course;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Inertia\Inertia;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $search = request()->input('search');

        $courses = Course::when($search, function ($query) use ($search) {
            $query->where('name', 'LIKE', '%' . $search . '%');
        })->with('staff')->paginate(10)->through(fn($item) => [
                "id" => $item->id,
                "code" => $item->code,
                "name" => $item->name,
                "staff" => [
                    "id" => $item->staff->id,
                    "name" => trim(
                        sprintf(
                            '%s %s %s',
                            $item->staff->surname,
                            $item->staff->first_name,
                            $item->staff->middle_name
                        )
                    ),
                ]
            ]);
        $instructors = Staff::whereHas('status', function ($query) {
            $query->where('name', 'current');
        })->where('teach', 1)->get()->map(fn($item) => [
                "id" => $item->id,
                "name" => sprintf("%s %s %s", $item->surname, $item->last_name, $item->middle_name),
            ]);
        return Inertia::render('Courses/Index', ['courses' => $courses, 'instructors' => $instructors, 'search' => $search,]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
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
            ->with('success', 'Course added');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Course $course
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Course $course)
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
            ->with('success', 'Course updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Course $course
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Course $course)
    {
        $course->delete();
        return redirect()
            ->back()
            ->with('success', 'Course deleted');
    }
}
