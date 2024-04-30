<?php
namespace App\Support;

use App\Models\Result;
use App\Models\Student;

class StudentUtil
{
    static function prepAdmissionNo(Student $student): string
    {
        $pattern = env('ADM_NUMBER_PATTERN');

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

    static function generateSummary($studentId)
    {
        // Retrieve results data for the student
        $results = Result::where('student_id', $studentId)
            ->join('examination_tests', 'examination_results.test_id', '=', 'examination_tests.id')
            ->join('examinations', 'examination_tests.examination_id', '=', 'examinations.id')
            ->join('subjects', 'examinations.subject_id', '=', 'subjects.id')
            ->select(
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
                // $subjectSummary['terms'][$termId] = [
                //     'total_marks' => $termSummary['total_marks'],
                //     'max_marks' => $termSummary['max_marks'],
                //     'average' => $termAverage
                // ];

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
}
