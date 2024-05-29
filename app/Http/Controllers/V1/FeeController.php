<?php

namespace App\Http\Controllers\V1;

use App\Models\Fee;
use App\Models\Term;
use Inertia\Inertia;
use App\Models\Course;
use App\Models\Student;
use Illuminate\Support\Str;
use App\Models\FeeTransaction;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreFeeRequest;
use App\Http\Requests\V1\UpdateFeeRequest;
use App\Models\FeeTransactionType;
use App\Models\FeeVoteHead;
use App\Support\StudentUtil;

class FeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $search = request()->input('search');

        $fees = Fee::when($search, function ($query) use ($search) {
            $query->where('name', 'like', '%' . $search . '%')
                ->orWhereHas('course', function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%')
                        ->orWhereHas('department', 'like', '%' . $search . '%');
                });
        })->paginate(8)->through(fn(Fee $fee) => [
                "id" => $fee->id,
                "term" => [
                    "id" => $fee->term->id,
                    "name" => sprintf("%d - %s", $fee->term->year, Str::title(Str::lower($fee->term->name)))
                ],
                "course" => [
                    "id" => $fee->course->id,
                    "name" => sprintf("%s - %s", Str::upper(Str::lower($fee->course->code)), Str::title(Str::lower($fee->course->name))),
                ],
                "amount" => $fee->amount,
                "vote_heads" => $fee->voteHeads->map(fn(FeeVoteHead $voteHead) => [
                    "id" => $voteHead->id,
                    "title" => $voteHead->title,
                    "share" => $voteHead->share,
                    "amount" => $voteHead->amount,
                ])
            ]);

        $terms = Term::all()->map(fn(Term $term) => [
            "id" => $term->id,
            "name" => sprintf("%d - %s", $term->year, Str::title(Str::lower($term->name))),
        ])->sortByDesc('name')->values();

        $courses = Course::all()->map(fn(Course $course) => [
            "id" => $course->id,
            "name" => sprintf("%s - %s", Str::upper(Str::lower($course->code)), Str::title(Str::lower($course->name))),
        ])->sortBy('name')->values();

        return Inertia::render('Accounts/Fees/Index', [
            'fees' => $fees,
            'terms' => $terms,
            'courses' => $courses,
            'search' => $search
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFeeRequest $request)
    {
        $fee = new Fee();
        $fee->term_id = $request->term;
        $fee->course_id = $request->course;
        $fee->amount = $request->amount;
        $fee->save();

        foreach ($request->vote_heads as $value) {
            $voteHead = new FeeVoteHead();
            if ($value['id']) {
                $voteHead = FeeVoteHead::find($value['id']);
            }

            $voteHead->title = $value['title'];
            $voteHead->share = $value['share'];
            $voteHead->amount = $value['amount'];
            $voteHead->fee_id = $fee->id;
            $voteHead->save();
        }

        $students = Student::whereHas('intake', function ($query) use ($fee) {
            $query->where('course_id', $fee->course_id);
        })->where('status', 'In Session')->get();

        $feeTransactionType = FeeTransactionType::where('code', 'FC')->first();

        foreach ($students as $student) {
            StudentUtil::postFeeTransaction($student, $fee, $feeTransactionType, $fee->amount);
        }

        return redirect()->back()->with('success', 'Fee stored');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFeeRequest $request, Fee $fee)
    {
        $fee = Fee::find($request->id);
        $fee->term_id = $request->term;
        $fee->course_id = $request->course;
        $fee->amount = $request->amount;
        $fee->save();

        $voteHeadIds = collect($request->vote_heads)->filter(fn($vh) => $vh['id'])->pluck('id')->values()->toArray();

        $deletedVoteHeads = FeeVoteHead::where('fee_id', $fee->id)->whereNotIn('id', $voteHeadIds)->get();
        if ($deletedVoteHeads) {
            foreach ($deletedVoteHeads as $voteHead) {
                $voteHead->delete();
            }
        }
        foreach ($request->vote_heads as $value) {
            $voteHead = new FeeVoteHead();
            if ($value['id']) {
                $voteHead = FeeVoteHead::find($value['id']);
            }

            $voteHead->title = $value['title'];
            $voteHead->share = $value['share'];
            $voteHead->amount = $value['amount'];
            $voteHead->fee_id = $fee->id;
            $voteHead->save();
        }

        FeeTransaction::whereHas('transaction_type', function ($query) {
            $query->where('code', 'like', 'FC');
        })->whereHas('fee', function ($query) use ($fee) {
            $query->where('id', $fee->id);
        })->whereHas('transaction_mode', function ($query) {
            $query->where('name', 'like', '%system%');
        })
            ->get()
            ->each(function (FeeTransaction $feeTransaction) {
                $feeTransaction->amount = $feeTransaction->fee->amount;
                $feeTransaction->save();
            });

        return redirect()->back()->with('success', 'Fee updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Fee $fee)
    {
        $fee->delete();
        return redirect()->back()->with('success', 'Fee deleted');
    }
}
