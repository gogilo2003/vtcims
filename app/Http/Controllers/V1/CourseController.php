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
        })->with('staff')->paginate(10)->through(fn ($item) => [
            "id" => $item->id,
            "code" => $item->code,
            "name" => $item->name,
            "staff" => [
                "id" => $item->staff->id,
                "name" => trim(sprintf(
                    '%s %s %s',
                    $item->staff->surname,
                    $item->staff->first_name,
                    $item->staff->middle_name
                )),
            ]
        ]);
        $instructors = Staff::whereHas('status', function ($query) {
            $query->where('name', 'current');
        })->where('teach', 1)->get()->map(fn ($item) => [
            "id" => $item->id,
            "name" => sprintf("%s %s %s", $item->surname, $item->last_name, $item->middle_name),
        ]);
        return Inertia::render('Courses/Index', ['courses' => $courses, 'instructors' => $instructors, 'search' => $search,]);
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
    public function show(Course $course)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Course $course)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Course $course)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course)
    {
        //
    }
}
