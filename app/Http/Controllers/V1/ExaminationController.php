<?php

namespace App\Http\Controllers\V1;

use Inertia\Inertia;
use App\Models\Intake;
use App\Models\Examination;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreExaminationRequest;
use App\Http\Requests\UpdateExaminationRequest;

class ExaminationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $search = request()->input('search');

        $examinations = Examination::with('subject', 'term', 'intakes', 'tests')
            ->orderBy('title', 'DESC')
            ->when($search, function ($query) use ($search) {
                $query->where('title', 'like', '%' . $search . '%');
            })->paginate(8)->through(fn(Examination $examination) => [
                "id" => $examination->id,
                "title" => $examination->title,
                "subject" => [
                    "id" => $examination->subject->id,
                    "name" => $examination->subject->name,
                ],
                "term" => [
                    "id" => $examination->term->id,
                    "year" => $examination->term->year,
                    "name" => $examination->term->name,
                ],
                "intakes" => $examination->intakes->map(fn(Intake $intake) => [
                    "id" => $intake->id,
                    "name" => $intake->name,
                ]),
            ]);

        return Inertia::render('Examinations/Index', [
            'examinations' => $examinations,
            "search" => $search
        ]);
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
    public function store(StoreExaminationRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Examination $examination)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Examination $examination)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateExaminationRequest $request, Examination $examination)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Examination $examination)
    {
        //
    }
}
