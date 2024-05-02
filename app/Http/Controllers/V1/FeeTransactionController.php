<?php

namespace App\Http\Controllers\V1;

use Inertia\Inertia;
use App\Models\Student;
use Illuminate\Support\Str;
use App\Support\StudentUtil;
use App\Models\FeeTransaction;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreFeeTransactionRequest;
use App\Http\Requests\V1\UpdateFeeTransactionRequest;

class FeeTransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $search = request()->input('search');
        $transactions = FeeTransaction::orderBy('id', 'DESC')->when($search, function ($query) use ($search) {
            $query->whereHas('student', function ($query) use ($search) {
                $names = explode(" ", $search);
                foreach ($names as $name) {
                    $query->where(function ($query) use ($name) {
                        $query->where('surname', 'like', '%' . $name . '%')
                            ->orWhere('first_name', 'like', '%' . $name . '%')
                            ->orWhere('middle_name', 'like', '%' . $name . '%');
                    });
                }
                $query->orWhere(function ($query) use ($search) {
                    $query->whereHas('intake.course.department', function ($query) use ($search) {
                        $query->where('name', 'like', '%' . $search . '%');
                    })->orWhereHas('intake.course', function ($query) use ($search) {
                        $query->where('name', 'like', '%' . $search . '%');
                    })->orWhereHas('intake', function ($query) use ($search) {
                        $query->where('name', 'like', '%' . $search . '%');
                    });
                });
            });
        })->paginate(8)->through(fn(FeeTransaction $feeTransaction) => $feeTransaction);

        $students = Student::where('status', 'In Session')->get()->map(fn(Student $student) => [
            "id" => $student->id,
            "name" => trim(
                sprintf(
                    "%s - %s%s %s",
                    StudentUtil::prepAdmissionNo($student),
                    Str::title(Str::lower($student->first_name)),
                    Str::title(Str::lower($student->middle_name ? " " . $student->middle_name : '')),
                    Str::title(Str::lower($student->surname))
                )
            )
        ])->sortBy('name')->values();

        return Inertia::render('Accounts/Transactions/Index', [
            'transactions' => $transactions,
            'students' => $students,
            'search' => $search
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFeeTransactionRequest $request)
    {

    }

    /**
     * Display the specified resource.
     */
    public function show(FeeTransaction $feeTransaction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FeeTransaction $feeTransaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFeeTransactionRequest $request, FeeTransaction $feeTransaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FeeTransaction $feeTransaction)
    {
        //
    }
}
