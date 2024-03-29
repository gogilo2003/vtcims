<?php

namespace App\Http\Controllers\V1;

use Inertia\Inertia;
use App\Models\Course;
use App\Models\Subject;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreSubjectRequest;
use App\Http\Requests\V1\UpdateSubjectRequest;

class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $search = request()->input('search');

        $subjects = Subject::when($search, function ($query) use ($search) {
            $query->where('name', 'LIKE', '%' . $search . '%');
        })->with('courses')->paginate(10)->through(fn ($item) => [
            "id" => $item->id,
            "code" => $item->code,
            "name" => $item->name,
            "courses" => $item->courses->map(fn ($item) => [
                "id" => $item->id,
                "code" => $item->code,
                "name" => $item->name,
            ])
        ]);

        $courses = Course::orderBy('name', 'ASC')->get()->map(fn ($item) => [
            "id" => $item->id,
            "code" => $item->code,
            "name" => $item->name,
        ]);

        return Inertia::render('Subjects/Index', ['subjects' => $subjects, 'courses' => $courses, 'search' => $search,]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSubjectRequest $request)
    {
        $subject = new Subject;

        $subject->name = $request->name;
        $subject->code = $request->code;

        $subject->save();

        $subject->courses()->attach($request->courses);
        $subject->staff()->attach($request->staff);

        return redirect()
            ->back()
            ->with('success', 'Subject added');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSubjectRequest $request, Subject $subject)
    {
        $subject->name = $request->subject_name;
        $subject->code = $request->subject_code;

        $subject->save();

        $subject->courses()->sync($request->courses);
        $subject->staff()->sync($request->staff);

        return redirect()
            ->back()
            ->with('global-success', 'Subject updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
