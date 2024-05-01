<?php
namespace App\Support;

use App\Models\Result;
use App\Models\Student;

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
        $pattern = env('ADM_NUMBER_PATTERN','{course}/{id}/{year}');

        // Retrieve the necessary data from the student model
        $department = $student->intake->course->department->code;
        $course = $student->intake->course->code;
        $studentId = $student->id;
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
        // Retrieve all results data for the student for all terms
        $allResults = Result::where('student_id', $studentId)
            ->join('examination_tests', 'examination_results.test_id', '=', 'examination_tests.id')
            ->join('examinations', 'examination_tests.examination_id', '=', 'examinations.id')
            ->join('subjects', 'examinations.subject_id', '=', 'subjects.id')
            ->select(
                'subjects.code',
                'subjects.name as subject',
                'examination_results.score'
            )
            ->get();

        // Initialize arrays for storing subject-wise scores
        $subjectScores = [];

        // Process all results data and organize scores by subject
        foreach ($allResults as $result) {
            $subject = $result->subject;
            $code = $result->code;
            $score = $result->score;

            if (!isset($subjectScores[$subject])) {
                $subjectScores[$subject] = [
                    'code' => $code,
                    'scores' => [],
                ];
            }

            $subjectScores[$subject]['scores'][] = $score;
        }

        // Retrieve results data for the current term
        $currentTermResults = Result::where('student_id', $studentId)
            ->join('examination_tests', 'examination_results.test_id', '=', 'examination_tests.id')
            ->join('examinations', 'examination_tests.examination_id', '=', 'examinations.id')
            ->join('subjects', 'examinations.subject_id', '=', 'subjects.id')
            ->where('examinations.term_id', $termId)
            ->select(
                'subjects.code',
                'subjects.name as subject',
                'examination_results.score'
            )
            ->get();

        // Initialize arrays for storing grades and remarks
        $transcriptData = [];

        // Process subject-wise scores and calculate mean, min, and max
        foreach ($subjectScores as $subject => $subjectData) {
            $code = $subjectData['code'];
            $allTimeScores = $subjectData['scores'];

            // Calculate all-time mean, min, and max
            $allTimeCount = count($allTimeScores);
            $allTimeMean = sprintf("%.1f", round($allTimeCount > 0 ? array_sum($allTimeScores) / $allTimeCount : 0));
            $allTimeMin = sprintf("%.1f", round($allTimeCount > 0 ? min($allTimeScores) : 0));
            $allTimeMax = sprintf("%.1f", round($allTimeCount > 0 ? max($allTimeScores) : 0));

            // Retrieve scores for the current term for the subject
            $currentTermSubjectScores = $currentTermResults->where('subject', $subject)->pluck('score')->toArray();

            // Calculate current term mean
            $currentTermMean = sprintf("%.1f", round(array_sum($currentTermSubjectScores)));

            // Calculate grade and remark for the current term using services
            $grade = self::calculateGrade($currentTermMean);
            $remark = self::generateRemark($grade);

            // Add subject data to transcript
            $transcriptData[] = (object) [
                'code' => $code,
                'subject' => $subject,
                'min' => $allTimeMin,
                'max' => $allTimeMax,
                'average' => $allTimeMean,
                'score' => $currentTermMean,
                'grade' => $grade,
                'remark' => $remark,
            ];
        }

        return collect($transcriptData)->sortBy('subject')->values();
    }

    /**
     * Summary of generateFeeSummary
     * @param mixed $studentId
     * @return \Illuminate\Support\Collection
     */
    static function generateFeeSummary($studentId)
    {
        return collect();
    }

}
