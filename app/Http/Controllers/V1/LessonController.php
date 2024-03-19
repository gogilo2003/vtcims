<?php

namespace App\Http\Controllers\V1;

use Inertia\Inertia;
use App\Models\Lesson;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreLessonRequest;
use App\Http\Requests\V1\UpdateLessonRequest;

class LessonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $search = request()->input('search');
        $lessons = Lesson::when($search, function ($query) use ($search) {
            $query->where('title', 'LIKE', "%$search%");
        })->with('allocations')->paginate(3)->through(fn ($item) => [
            "id" => $item->id,
            "title" => $item->title,
            "day" => $item->day,
            "start_at" => $item->start_at->format('H:i'),
            "end_at" => $item->end_at->format('H:i'),
        ]);
        return Inertia::render('Lessons/Index', ['lessons' => $lessons, 'search' => $search]);
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
    public function store(StoreLessonRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Lesson $lesson)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Lesson $lesson)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLessonRequest $request, Lesson $lesson)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Lesson $lesson)
    {
        //
    }
}
