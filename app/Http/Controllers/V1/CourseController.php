<?php

namespace App\Http\Controllers\V1;

use Inertia\Inertia;
use App\Models\Staff;
use App\Models\Course;
use App\Models\Department;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreCourseRequest;
use App\Http\Requests\V1\UpdateCourseRequest;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $search = request()->input('search');

        $courses = Course::when($search, function ($query) use ($search) {
            $query->where('name', 'LIKE', '%' . $search . '%')
                ->orWhere('code', 'like', '%' . $search . '%');
        })->with('staff')->paginate(10)->through(fn($item) => [
                "id" => $item->id,
                "code" => $item->code,
                "name" => $item->name,
                "duration" => explode(" ", $item->duration)[0],
                "internship_duration" => $item->internship_duration,
                "department" => [
                    "id" => $item->department->id,
                    "name" => $item->department->name,
                ],
                "staff" => [
                    "id" => $item->staff->id,
                    "name" => trim(
                        Str::title(
                            Str::lower(
                                sprintf(
                                    '%s%s %s',
                                    $item->staff->first_name,
                                    $item->staff->middle_name ? " " . $item->staff->middle_name : '',
                                    $item->staff->surname
                                )
                            )
                        )
                    ),
                ]
            ]);
        $instructors = Staff::whereHas('status', function ($query) {
            $query->where('name', 'current');
        })->where('teach', 1)->get()->map(fn($item) => [
                "id" => $item->id,
                "name" => Str::title(Str::lower(sprintf("%s %s", $item->first_name, $item->surname))),
            ]);

        $departments = Department::all()->map(fn(Department $dept) => [
            "id" => $dept->id,
            "name" => Str::upper($dept->name),
        ]);

        return Inertia::render('Courses/Index', [
            'courses' => $courses,
            'instructors' => $instructors,
            'departments' => $departments,
            'search' => $search,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreCourseRequest $request)
    {
        $course = new Course;
        $course->department_id = $request->department;
        $course->code = $request->code;
        $course->name = $request->name;
        $course->duration = $request->duration;
        $course->internship_duration = $request->internship_duration;
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
    public function update(UpdateCourseRequest $request, Course $course)
    {
        $course = Course::find($request->id);
        $course->department_id = $request->department;
        $course->code = $request->code;
        $course->name = $request->name;
        $course->internship_duration = $request->internship_duration;
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
