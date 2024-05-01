<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreFeeRequest;
use App\Http\Requests\V1\UpdateFeeRequest;
use App\Models\Fee;
use Inertia\Inertia;

class FeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $search = request()->input('search');
        $fees = Fee::when($search, )->paginate(8)->through(fn(Fee $fee) => [
            "id" => $fee->id,
            "term" => $fee->term,
            "course" => $fee->course,
            "amount" => $fee->amount,
        ]);
        return Inertia::render('Accounts/Fees/Index', ['fees' => $fees, 'terms' => $terms, 'courses' => $courses]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFeeRequest $request)
    {
        $validator = Validator::make($request->all(), [
            'term' => [
                'required',
                'exists:terms,id',
                Rule::unique('fees', 'term_id')->where(function ($query) use ($request) {
                    return $query->where('course_id', $request->course);
                })
            ],
            'course' => 'required|exists:courses,id',
            'amount' => 'required|numeric',
        ], [
            'term.unique' => 'The fee you tried to create already exists'
        ]);

        if ($validator->fails()) {
            return $this->validationError($validator);
        }

        $fee = new Fee();
        $fee->term_id = $request->term;
        $fee->course_id = $request->course;
        $fee->amount = $request->amount;
        $fee->save();

        return redirect()->back()->with('success', 'Fee stored');
    }

    /**
     * Display the specified resource.
     */
    public function show(Fee $fee)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Fee $fee)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFeeRequest $request, Fee $fee)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Fee $fee)
    {
        //
    }
}
