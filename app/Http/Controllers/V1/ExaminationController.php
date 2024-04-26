<?php

namespace App\Http\Controllers\V1;

use App\Models\Test;
use Inertia\Inertia;
use App\Models\Intake;
use App\Models\Result;
use App\Models\Student;
use App\Models\Examination;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreExaminationRequest;
use App\Http\Requests\UpdateExaminationRequest;
use App\Support\StudentUtil;

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
        return Inertia::render('Examinations/View', [
            'examination' => [
                "id" => $examination->id,
                "title" => $examination->title,
                "intakes" => $examination->intakes->map(fn(Intake $intake) => [
                    "id" => $intake->id,
                    "name" => $intake->name,
                ]),
                "tests" => $examination->tests->map(fn(Test $test) => [
                    "id" => $test->id,
                    "title" => $test->title,
                ]),
                "students" => $examination->intakes->flatMap->students->map(
                    function (Student $student) use ($examination) {
                        $student->load([
                            'results' => function ($query) use ($examination) {
                                $query->whereHas(
                                    'test',
                                    function ($query) use ($examination) {
                                        $query->where('examination_id', $examination->id);
                                    }
                                );
                            }
                        ]);
                        return [
                            "id" => $student->id,
                            "admission_no" => StudentUtil::prepAdmissionNo($student),
                            "name" => Str::title(
                                Str::lower(
                                    sprintf(
                                        "%s%s %s",
                                        $student->first_name,
                                        $student->middle_name ? ' ' . $student->middle_name : '',
                                        $student->surname
                                    )
                                )
                            ),
                            "results" => $examination->tests->map(
                                function (Test $test) use ($student) {
                                    if ($result = $student->results->where('test_id', $test->id)->first()) {
                                        return [
                                            "id" => $result->id,
                                            "test_id" => $result->test_id,
                                            "score" => $result->score,
                                        ];
                                    }
                                    return [
                                        "id" => null,
                                        "test_id" => $test->id,
                                        "score" => null,
                                    ];
                                }
                            ),
                        ];
                    }
                )->sortBy('admission_no')->values(),
            ]
        ]);
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

    function marklist()
    {

    }
}
