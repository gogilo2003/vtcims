<?php
namespace App\Support;

use Carbon\Carbon;
use App\Models\Fee;
use App\Models\Result;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Support\Str;
use App\Models\FeeTransaction;
use App\Models\FeeTransactionMode;
use App\Models\FeeTransactionType;
use Illuminate\Support\Facades\Storage;

/**
 * StudentUtil
 *
 * Utility functions for student
 */
class StudentUtil
{
    /**
     * Summary of prepAdmissionNo
     * @param \App\Models\Student $student
     * @return string
     */
    static function prepAdmissionNo(Student $student): string
    {
        if ($student->old_admission_no) {
            return $student->old_admission_no;
        }
        $pattern = config('eschool.adm_number_pattern');

        // Retrieve the necessary data from the student model
        $department = $student->intake->course->department->code;
        $course = $student->intake->course->code;
        $studentId = str_pad($student->id, 4, '0', STR_PAD_LEFT);
        $year = date('Y', strtotime($student->date_of_admission));

        // Replace placeholders in the pattern with actual values
        $admissionNumber = str_replace(
            ['{department}', '{course}', '{id}', '{year}'],
            [$department, $course, $studentId, $year],
            $pattern
        );

        return $admissionNumber;
    }

    /**
     * Summary of generateExamSummary
     * @param mixed $studentId
     * @return \Illuminate\Support\Collection
     */
    static function generateExamSummary($studentId)
    {
        // Retrieve results data for the student
        $results = Result::where('student_id', $studentId)
            ->join('examination_tests', 'examination_results.test_id', '=', 'examination_tests.id')
            ->join('examinations', 'examination_tests.examination_id', '=', 'examinations.id')
            ->join('subjects', 'examinations.subject_id', '=', 'subjects.id')
            ->select(
                'subjects.code',
                'subjects.name as subject',
                'examinations.term_id',
                'examination_tests.outof as max_marks',
                'examination_results.score'
            )
            ->get();

        // Organize the fetched data into a summary format
        $summary = [];
        foreach ($results as $result) {
            $subject = $result->subject;
            $termId = $result->term_id;
            $maxMarks = $result->max_marks;
            $score = $result->score;

            if (!isset($summary[$subject][$termId])) {
                $summary[$subject][$termId] = [
                    'code' => $result->code,
                    'total_marks' => 0,
                    'max_marks' => $maxMarks,
                    'tests_count' => 0,
                    'tests' => []
                ];
            }

            $remainingMarks = $maxMarks - $summary[$subject][$termId]['total_marks'];
            $normalizedScore = min($score, $remainingMarks);

            $summary[$subject][$termId]['total_marks'] += $normalizedScore;
            $summary[$subject][$termId]['tests_count']++;
            $summary[$subject][$termId]['tests'][] = [
                'score' => $normalizedScore,
                'max_marks' => $maxMarks
            ];
        }

        // Calculate average, min, and max for every subject
        $subjectSummaries = [];
        foreach ($summary as $subject => $terms) {
            $subjectSummary = [
                'subject' => $subject,
                // 'terms' => [],
                'average' => 0,
                'min' => PHP_INT_MAX,
                'max' => 0
            ];

            foreach ($terms as $termId => $termSummary) {

                $termAverage = $termSummary['total_marks'] / $termSummary['tests_count'];

                $subjectSummary['code'] = $termSummary['code'];
                $subjectSummary['average'] += $termAverage;
                $subjectSummary['min'] = min($subjectSummary['min'], $termAverage);
                $subjectSummary['max'] = max($subjectSummary['max'], $termAverage);
            }

            $subjectSummary['average'] /= count($terms);

            $subjectSummary['min'] = strpos(round($subjectSummary['min'], 1), '.') !== false
                ? round($subjectSummary['min'], 1)
                : sprintf("%.1f", $subjectSummary['min']);

            $subjectSummary['max'] = strpos(round($subjectSummary['max'], 1), '.') !== false
                ? round($subjectSummary['max'], 1)
                : sprintf("%.1f", $subjectSummary['max']);

            $subjectSummary['average'] = strpos(round($subjectSummary['average'], 1), '.') !== false
                ? round($subjectSummary['average'], 1)
                : sprintf("%.1f", $subjectSummary['average']);

            $subjectSummaries[] = (object) $subjectSummary;
        }
        // usort($subjectSummaries, function ($a, $b) {
        //     return $b['average'] - $a['average'];
        // });

        $subjectSummaries = collect($subjectSummaries)->sortByDesc('average')->values();

        return $subjectSummaries;
    }

    /**
     * Summary of doGrade
     * @param mixed $score
     * @return int
     */
    static function calculateGrade(float $score)
    {

        $grade = 1;
        if ($score > 90) {
            $grade = 1;
        } elseif ($score >= 80) {
            $grade = 2;
        } elseif ($score >= 70) {
            $grade = 3;
        } elseif ($score >= 60) {
            $grade = 4;
        } elseif ($score >= 50) {
            $grade = 5;
        } elseif ($score >= 40) {
            $grade = 6;
        } elseif ($score >= 30) {
            $grade = 7;
        } elseif ($score >= 20) {
            $grade = 8;
        } else {
            $grade = 9;
        }
        // if ($score == 58)
        //     dd($score, $grade);
        return $grade;
    }

    /**
     * Summary of doRemark
     * @param mixed $grade
     * @return string
     */
    static function generateRemark($grade)
    {
        $remark = '';
        if (((int) $grade == 2) || ((int) $grade == 1)) {
            $remark = 'Distinction';
        } elseif (((int) $grade == 3) || ((int) $grade == 4)) {
            $remark = "Credit";
        } elseif (((int) $grade == 5) || ((int) $grade == 6)) {
            $remark = "Pass";
        } elseif ((int) $grade == 7) {
            $remark = "Referred";
        } else {
            $remark = "Fail";
        }

        return $remark;
    }

    /**
     * Summary of prepareTranscript
     * @param mixed $studentId
     * @param mixed $termId
     * @return \Illuminate\Support\Collection
     */
    static function prepareTranscript($studentId, $termId)
    {
        // Get the student and intake
        $student = Student::with('intake')->find($studentId);

        if (!$student) {
            return response()->json(['error' => 'Student not found'], 404);
        }

        // Get subjects for the student's course
        $subjects = Subject::whereHas('courses', function ($query) use ($student) {
            $query->where('courses.id', $student->intake->course_id);
        })->get();

        // Prepare the results
        $results = [];

        foreach ($subjects as $subject) {
            // Get the current term result
            $curTermResults = Result::whereHas('test.examination', function ($query) use ($termId, $subject) {
                $query->where('term_id', $termId)
                    ->where('subject_id', $subject->id);
            })->where('student_id', $studentId)->get();

            $currentTermResult = null;

            foreach ($curTermResults as $result) {
                $currentTermResult += $result->score / $result->test->outof * 100;
            }

            if ($curTermResults->count()) {
                $currentTermResult /= $curTermResults->count();
            }

            // Get all-time statistics for the subject
            $allTimeResults = Result::whereHas('test.examination', function ($query) use ($subject) {
                $query->where('subject_id', $subject->id);
            })->where('student_id', $studentId)->pluck('score');

            $mean = $allTimeResults->avg();
            $min = $allTimeResults->min();
            $max = $allTimeResults->max();

            $grade = $currentTermResult ? self::calculateGrade($currentTermResult) : null;
            $remark = $grade ? self::generateRemark($grade) : null;
            $results[] = (object) [
                'code' => $subject->code,
                'subject' => $subject->name,
                'score' => $currentTermResult ? sprintf("%.1f", round($currentTermResult)) : null,
                'average' => $mean ? sprintf("%.1f", round($mean)) : null,
                'min' => $min,
                'max' => $max,
                'grade' => $grade,
                'remark' => $remark,
            ];
        }

        return collect($results);
    }
    // static function prepareTranscript($studentId, $termId)
    // {
    //     // Retrieve all results data for the student for all terms
    //     $allResults = Result::where('student_id', $studentId)
    //         ->join('examination_tests', 'examination_results.test_id', '=', 'examination_tests.id')
    //         ->join('examinations', 'examination_tests.examination_id', '=', 'examinations.id')
    //         ->join('subjects', 'examinations.subject_id', '=', 'subjects.id')
    //         ->select(
    //             'subjects.code',
    //             'subjects.name as subject',
    //             'examination_results.score'
    //         )
    //         ->get();

    //     // Initialize arrays for storing subject-wise scores
    //     $subjectScores = [];

    //     // Process all results data and organize scores by subject
    //     foreach ($allResults as $result) {
    //         $subject = $result->subject;
    //         $code = $result->code;
    //         $score = $result->score;

    //         if (!isset($subjectScores[$subject])) {
    //             $subjectScores[$subject] = [
    //                 'code' => $code,
    //                 'scores' => [],
    //             ];
    //         }

    //         $subjectScores[$subject]['scores'][] = $score;
    //     }

    //     // Retrieve results data for the current term
    //     $currentTermResults = Result::where('student_id', $studentId)
    //         ->join('examination_tests', 'examination_results.test_id', '=', 'examination_tests.id')
    //         ->join('examinations', 'examination_tests.examination_id', '=', 'examinations.id')
    //         ->join('subjects', 'examinations.subject_id', '=', 'subjects.id')
    //         ->where('examinations.term_id', $termId)
    //         ->select(
    //             'subjects.code',
    //             'subjects.name as subject',
    //             'examination_results.score'
    //         )
    //         ->get();

    //     // Initialize arrays for storing grades and remarks
    //     $transcriptData = [];

    //     // Process subject-wise scores and calculate mean, min, and max
    //     foreach ($subjectScores as $subject => $subjectData) {
    //         $code = $subjectData['code'];
    //         $allTimeScores = $subjectData['scores'];

    //         // Calculate all-time mean, min, and max
    //         $allTimeCount = count($allTimeScores);
    //         $allTimeMean = sprintf("%.1f", round($allTimeCount > 0 ? array_sum($allTimeScores) / $allTimeCount : 0));
    //         $allTimeMin = sprintf("%.1f", round($allTimeCount > 0 ? min($allTimeScores) : 0));
    //         $allTimeMax = sprintf("%.1f", round($allTimeCount > 0 ? max($allTimeScores) : 0));

    //         // Retrieve scores for the current term for the subject
    //         $currentTermSubjectScores = $currentTermResults->where('subject', $subject)->pluck('score')->toArray();

    //         // Calculate current term mean
    //         $currentTermMean = sprintf("%.1f", round(array_sum($currentTermSubjectScores)));
    //         // dd($currentTermMean);
    //         // Calculate grade and remark for the current term using services
    //         $grade = self::calculateGrade($currentTermMean);
    //         $remark = self::generateRemark($grade);

    //         // Add subject data to transcript
    //         $transcriptData[] = (object) [
    //             'code' => $code,
    //             'subject' => $subject,
    //             'min' => $allTimeMin,
    //             'max' => $allTimeMax,
    //             'average' => $allTimeMean,
    //             'score' => $currentTermMean,
    //             'grade' => $grade,
    //             'remark' => $remark,
    //         ];
    //     }

    //     return collect($transcriptData)->sortBy('subject')->values();
    // }

    /**
     * Post new Fee Transaction
     *
     * @param \App\Models\Student $student
     * @param \App\Models\Fee $fee
     * @param \App\Models\FeeTransactionType $feeTransactionType
     * @param float $amount
     * @return void
     */
    static function postFeeTransaction(Student $student, Fee $fee, FeeTransactionType $feeTransactionType, float $amount, FeeTransactionMode $mode = null)
    {
        if (is_null($mode)) {
            $mode = FeeTransactionMode::where('name', 'like', '%system%')->first();
        }
        $feeTransaction = new FeeTransaction();
        $feeTransaction->particulars = sprintf(
            "%s for %s, %s-%s",
            $feeTransactionType->description,
            Str::title(
                Str::lower(
                    sprintf(
                        "%s%s %s(%s)",
                        $student->first_name,
                        $student->middle_name ? " " . $student->middle_name : '',
                        $student->surname,
                        StudentUtil::prepAdmissionNo($student)
                    )
                )
            ),
            $fee->term->year,
            Str::title(Str::lower($fee->term->name))
        );
        $feeTransaction->student_id = $student->id;
        $feeTransaction->fee_id = $fee->id;
        $feeTransaction->amount = $amount;
        $feeTransaction->transaction_mode_id = $mode->id;

        $feeTransactionType->fee_transactions()->save($feeTransaction);
    }

    static function getPhotoUrl($filePath, $base64 = false)
    {
        $defaultImagePath = 'img/person_8x10.png'; // Update this with your actual path
        // $defaultImageBase64 = ''; // Add your base64 representation here if needed

        if ($filePath) {
            if ($base64) {
                // Return base64 URL of the image
                $imageType = pathinfo(storage_path('app/public/' . $filePath), PATHINFO_EXTENSION);
                $imageData = file_get_contents(storage_path('app/public/' . $filePath));
                $base64Image = 'data:image/' . $imageType . ';base64,' . base64_encode($imageData);
                return $base64Image;
            } else {
                // Return regular path
                return Storage::disk('public')->url($filePath);
            }
        } else {
            if ($base64) {
                // Return base64 URL of the default image
                $defaultImageType = pathinfo(public_path($defaultImagePath), PATHINFO_EXTENSION);
                $defaultImageData = file_get_contents(public_path($defaultImagePath));
                $defaultBase64Image = 'data:image/' . $defaultImageType . ';base64,' . base64_encode($defaultImageData);
                return $defaultBase64Image;
            } else {
                // Return regular path of the default image
                return asset($defaultImagePath);
            }
        }
    }

    /**
     * generates a aggregate of student's school fees
     * @param mixed $studentId
     * @return object
     */
    static function generateFeeSummary($studentId)
    {
        // Fetch fee transactions for the student
        $transactions = FeeTransaction::where('student_id', $studentId)->get();

        // Initialize variables for total fee charged, total fee paid, and fee balance
        $totalFeeCharged = 0;
        $totalFeePaid = 0;
        $feeBalance = 0;

        // Process each transaction
        foreach ($transactions as $transaction) {
            if ($transaction->transaction_type->code == 'FC') {
                // Fee charged
                $totalFeeCharged += $transaction->amount;
            } elseif ($transaction->transaction_type->code == 'FP') {
                // Fee paid
                $totalFeePaid += $transaction->amount;
            } elseif ($transaction->transaction_type->code == 'FR') {
                // Fee reversed (deduct from total fee paid)
                $totalFeePaid -= $transaction->amount;
            }
        }

        // Calculate fee balance
        $feeBalance = $totalFeeCharged - $totalFeePaid;

        // Return summary
        return (object) [
            'charged' => $totalFeeCharged,
            'paid' => $totalFeePaid,
            'balance' => $feeBalance
        ];
    }

    static function summarizeFeePaymentsByMonth($studentId)
    {
        // Get the current year
        $currentYear = Carbon::now()->year;

        // Get the fee payment data for the selected student for the current year
        $feePayments = FeeTransaction::where('student_id', $studentId)
            ->whereYear('created_at', $currentYear)
            ->get();

        // Initialize a collection to store the fee payment summary
        $summary = collect();

        // Loop through the fee payments and aggregate them by month
        foreach ($feePayments as $payment) {
            $month = Carbon::parse($payment->created_at)->format('F'); // Get the month name
            $amount = $payment->amount;
            $transactionType = $payment->transaction_type;

            // If the month already exists in the summary collection, update it; otherwise, add a new entry
            $summary->transform(function ($item) use ($month, $amount, $transactionType) {
                if ($item['month'] === $month) {
                    if ($transactionType === 'FC') {
                        // Fee charged
                        $item['charged'] += $amount;
                    } elseif ($transactionType === 'FP') {
                        // Fee paid
                        $item['paid'] += $amount;
                    } elseif ($transactionType === 'FR') {
                        // Fee reversed
                        $item['paid'] -= $amount;
                    }
                    $item['balance'] = $item['charged'] - $item['paid'];
                }
                return $item;
            });

            if (!$summary->contains('month', $month)) {
                // Create a new entry for the month
                $summary->push([
                    'month' => $month,
                    'charged' => ($transactionType === 'FC') ? $amount : 0,
                    'paid' => ($transactionType === 'FP') ? $amount : 0,
                    'balance' => 0 // Balance will be updated in the transform function
                ]);
            }
        }

        // Convert each month's summary into an object
        $summary->transform(function ($item) {
            return (object) $item;
        });

        // Return the fee payment summary as a collection
        return $summary;
    }
}
