<?php

namespace App\Http\Controllers\V1;

use App\Models\Fee;
use Inertia\Inertia;
use App\Support\Util;
use App\Models\Student;
use Illuminate\Support\Str;
use App\Support\StudentUtil;
use App\Models\FeeTransaction;
use Illuminate\Support\Carbon;
use App\Models\FeeTransactionMode;
use App\Models\FeeTransactionType;
use Illuminate\Support\Facades\App;
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
                $admission = explode("/", $search);
                if (count($admission) == 3 || count($admission) == 4) {
                    $pattern = explode("/", Str::replace("}", "", Str::replace("{", "", config('eschool.adm_number_pattern'))));

                    $course = $admission[array_search('course', $pattern)];
                    $department = $admission[array_search('department', $pattern)];
                    $id = $admission[array_search('id', $pattern)];
                    $year = $admission[array_search('year', $pattern)];

                    $query->orWhereHas('intake', function ($query) use ($course, $department) {
                        $query->when($course, function ($query) use ($course) {
                            $query->where('course_id', $course);
                        });
                        $query->when($department, function ($query) use ($department) {
                            $query->whereHas('course', function ($query) use ($department) {
                                $query->where('department_id', $department);
                            });
                        });
                    });

                    $query->orWhere('id', $id);
                }
            });
        })->paginate(8)
            ->through(fn(FeeTransaction $feeTransaction) => [
                "id" => $feeTransaction->id,
                "particulars" => $feeTransaction->particulars,
                "amount" => $feeTransaction->amount,
                "student" => [
                    "id" => $feeTransaction->student->id,
                    "admission_no" => StudentUtil::prepAdmissionNo($feeTransaction->student),
                    "name" => Str::title(
                        Str::lower(
                            sprintf(
                                "%s%s %s",
                                $feeTransaction->student->first_name,
                                $feeTransaction->student->middle_name ? ' ' . $feeTransaction->student->middle_name : '',
                                $feeTransaction->student->surname
                            )
                        )
                    )
                ],
                "fee" => [
                    "id" => $feeTransaction->fee->id,
                    "name" => sprintf(
                        "%d-%s : %s",
                        $feeTransaction->fee->term->year,
                        Str::title(Str::lower($feeTransaction->fee->term->name)),
                        Str::title(Str::lower($feeTransaction->fee->course->name))
                    ),
                ],
                "mode" => [
                    "id" => $feeTransaction->transaction_mode->id,
                    "name" => $feeTransaction->transaction_mode->name,
                ],
                "receipt" => $feeTransaction->transaction_type->code == 'FP' ? true : false,
                "date" => $feeTransaction->created_at->isoFormat('ddd, D MMM Y HH:mm:s A'),
            ]);

        $students = Student::where('status', 'In Session')
            ->get()
            ->map(fn(Student $student) => [
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

        $transaction_types = FeeTransactionType::all()->map(fn(FeeTransactionType $ftp) => [
            "id" => $ftp->id,
            "name" => sprintf(
                "%s-%s",
                Str::upper(Str::lower($ftp->code)),
                Str::title(Str::lower($ftp->description))
            ),
        ])->sortBy('name')->values();

        $transaction_modes = FeeTransactionMode::where('name', 'not like', '%system%')->get()->map(fn(FeeTransactionMode $ftp) => [
            "id" => $ftp->id,
            "name" => Str::upper(Str::lower($ftp->name)),
        ])->sortBy('name')->values();

        $fees = Fee::all()->map(fn(Fee $fee) => [
            "id" => $fee->id,
            "name" => sprintf(
                "%s-%s:%s",
                $fee->term->year,
                Str::title(Str::lower($fee->term->name)),
                Str::title(Str::lower($fee->course->name))
            ),
        ])->sortByDesc('name')->values();

        return Inertia::render('Accounts/Transactions/Index', [
            'transactions' => $transactions,
            'students' => $students,
            'transaction_types' => $transaction_types,
            'transaction_modes' => $transaction_modes,
            'fees' => $fees,
            'search' => $search
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFeeTransactionRequest $request)
    {
        $student = Student::find($request->student);
        $fee = Fee::find($request->fee);
        $feeTransactionType = FeeTransactionType::where("code", $request->type)->first();
        $feeTransactionMode = FeeTransactionMode::find($request->mode);

        StudentUtil::postFeeTransaction($student, $fee, $feeTransactionType, $request->amount, $feeTransactionMode);

        return redirect()->back()->with('success', sprintf("%s transaction posted", $feeTransactionType->description));
    }

    /**
     * Download in pdf the selected fee transaction record
     * @param \App\Models\FeeTransaction $feeTransaction
     * @return void
     */
    public function download(FeeTransaction $feeTransaction)
    {

        $viewName = 'pdf.transactions.receipt';
        if (file_exists(resource_path('views/pdf/custom/transactions/receipt'))) {
            $viewName = 'pdf.custom.transactions.receipt';
        }

        $data = [
            'number' => sprintf("#%s", str_pad($feeTransaction->id, 6, "0", STR_PAD_LEFT)),
            'description' => $feeTransaction->particulars,
            'amount' => 'Ksh ' . number_format($feeTransaction->amount),
            'txDate' => Carbon::parse($feeTransaction->created_at)->isoFormat('ddd, D MMM, Y h:m:s a'),
            'logo' => Util::getImageBase64(public_path('logo.png')),
        ];
        $pdf = App::make('snappy.pdf.wrapper');

        $pdf->setOption("page-width", "58mm")
            ->setOption("page-height", "150mm")
            ->setOption("margin-left", 0)
            ->setOption("margin-right", 0)
            // ->setOption("footer-center", "Page [page] of [toPage]")
            ->setOption("footer-font-size", 7)
            ->loadView($viewName, $data);

        return $pdf->download(sprintf('Receipt-%d.pdf', $feeTransaction->id));
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
