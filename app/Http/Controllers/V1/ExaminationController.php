<?php

namespace App\Http\Controllers\V1;

use App\Models\Test;
use Inertia\Inertia;
use App\Models\Intake;
use App\Models\Result;
use App\Models\Student;
use App\Models\Examination;
use Illuminate\Support\Str;
use App\Support\StudentUtil;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreExaminationRequest;
use App\Http\Requests\V1\UpdateExaminationRequest;

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
        if ($request->has('students')) {
            foreach ($request->students as $student) {
                foreach ($student['marks'] as $score) {
                    if ($score) {
                        if ($score['score']) {
                            $result = $score['id'] ? Result::find($score['id']) : new Result;
                            $result->test_id = $score['test_id'];
                            $result->score = $score['score'];
                            $result->student_id = $student['student_id'];
                            $result->save();
                        }
                    }
                }
            }
        }

        return redirect()->back()->with('success', 'Examination results saved');
    }

    /**
     * Display the specified resource.
     */
    public function show(Examination $examination)
    {
        $examination = [
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

                    $results = $examination->tests->map(function (Test $test) use ($student) {
                        $result = $student->results->where('test_id', $test->id)->first();

                        $res = null;

                        if ($result) {
                            $res = (object) [
                                "student_id" => $student->id,
                                "id" => $result->id,
                                "test_id" => $result->test_id,
                                "score" => $result->score,
                                "max" => $test->outof,
                            ];
                        } else {
                            $res = (object) [
                                "student_id" => $student->id,
                                "id" => null,
                                "test_id" => $test->id,
                                "score" => null,
                                "max" => $test->outof,
                            ];
                        }
                        return $res;
                    });

                    $total = 0;
                    foreach ($results as $result) {
                        if ($result->score) {
                            $total += $result->score;
                        }
                    }

                    $grade = do_grade($total);
                    $remark = do_remarks($grade);

                    return (object) [
                        "id" => $student->id,
                        "admission_no" => StudentUtil::prepAdmissionNo($student),
                        "name" => Str::title(
                            Str::lower(
                                sprintf(
                                    "%s%s %s",
                                    $student->first_name,
                                    $student->middle_name ? " " . $student->middle_name : "",
                                    $student->surname
                                )
                            )
                        ),
                        "results" => $results,
                        "total" => $total,
                        "grade" => $grade,
                        "remarks" => $remark,
                    ];
                }
            )->sortBy('admission_no')->values(),
        ];

        return Inertia::render('Examinations/View', ['examination' => $examination]);
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

    function marklist(Examination $examination)
    {
        $pdf = App::make('snappy.pdf.wrapper');
        // $examination = Examination::with('tests')->find($id);
        $examination->load('tests');
        $examination->load(['intakes.students.results']);
        $intakes = implode(', ', $examination->intakes->pluck('name')->toArray());
        $blank = request()->input('blank');

        $data = [
            'examination' => (object) [
                "id" => $examination->id,
                "title" => $examination->title,
                "tests" => $examination->tests,
                "students" => $examination->intakes->flatMap->students->map(
                    function (Student $student) use ($examination) {

                        $results = $examination->tests->map(function (Test $test) use ($student) {
                            $result = $student->results->where('test_id', $test->id)->first();

                            $res = null;

                            if ($result) {
                                $res = (object) [
                                    "student_id" => $student->id,
                                    "id" => $result->id,
                                    "test_id" => $result->test_id,
                                    "score" => $result->score,
                                    "max" => $test->outof,
                                ];
                            } else {
                                $res = (object) [
                                    "student_id" => $student->id,
                                    "id" => null,
                                    "test_id" => $test->id,
                                    "score" => null,
                                    "max" => $test->outof,
                                ];
                            }
                            return $res;
                        });
                        $total = 0;
                        foreach ($results as $result) {
                            if ($result->score) {
                                $total += $result->score;
                            }
                        }

                        $grade = do_grade($total);
                        $remark = do_remarks($grade);

                        return (object) [
                            "admission_no" => StudentUtil::prepAdmissionNo($student),
                            "name" => Str::title(
                                Str::lower(
                                    sprintf(
                                        "%s%s %s",
                                        $student->first_name,
                                        $student->middle_name ? " " . $student->middle_name : "",
                                        $student->surname
                                    )
                                )
                            ),
                            "results" => $results,
                            "total" => $total,
                            "grade" => $grade,
                            "remarks" => $remark,
                        ];
                    }
                )->sortBy('admission_no')->values(),

            ],
            'intakes' => $intakes
        ];

        if ($blank) {
            $data['blank'] = true;
        }

        $pdf->loadView('pdf.examinations.examination', $data)
            ->setPaper('A4')
            ->setOption('no-outline', true)
            ->setOption('footer-center', 'Page [page] of [toPage]')
            ->setOption('footer-font-size', 8);

        $filename = explode('-', $examination->title);
        foreach ($filename as $key => $value) {
            $filename[$key] = trim($value);
        }
        $filename = strtoupper(str_replace('-', '_', str_replace(' ', '-', implode('-', $filename)))) . '.pdf';

        return $pdf->stream($filename);
    }
}
