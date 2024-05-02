<?php

namespace App\Http\Controllers\V1;

use App\Models\Fee;
use App\Models\Term;
use Inertia\Inertia;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreFeeRequest;
use App\Http\Requests\V1\UpdateFeeRequest;
use App\Models\Course;
use App\Models\FeeTransaction;

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

        $transactions = FeeTransaction::whereHas('transaction_type', function ($query) {
            $query->where('code', 'like', 'FC');
        })->get()->each(function (FeeTransaction $feeTransaction) {
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
