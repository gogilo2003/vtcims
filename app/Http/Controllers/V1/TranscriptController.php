<?php

namespace App\Http\Controllers\V1;

use App\Models\Term;
use Inertia\Inertia;
use App\Models\Student;
use App\Support\StudentUtil;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;

class TranscriptController extends Controller
{
    function index()
    {
        $terms = Term::orderBy('id', 'DESC')
            ->get()
            ->map(fn(Term $term) => (object) [
                "id" => $term->id,
                "name" => $term->name,
                "year" => $term->year,
                "start_date" => $term->start_date->isoFormat('ddd, D MMM Y'),
                "end_date" => $term->end_date->isoFormat('ddd, D MMM Y'),
            ]);

        $search = request()->input('search');
        $term = request()->input('term') ? Term::find(request()->input('term')) : $terms->first();

        $transcripts = Student::where('status', 'In Session')
            ->when($search, function ($query) use ($search) {
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
            })
            ->get()
            ->map(function (Student $student) use ($term) {
                $marks = StudentUtil::prepareTranscript($student->id, $term->id);

                $total = 0;
                $count = 0;
                foreach ($marks as $mark) {
                    $total += $mark->score;
                    $count++;
                }
                $average = sprintf("%.1f", round($count > 0 ? $total / $count : 0, 1));
                $grade = StudentUtil::calculateGrade($average);
                $remark = StudentUtil::generateRemark($grade);

                return (object) [
                    "admission_no" => StudentUtil::prepAdmissionNo($student),
                    "name" => sprintf('%s%s %s', $student->first_name, $student->middle_name ? ' ' . $student->middle_name : '', $student->surname),
                    "intake" => $student->intake->name,
                    "course" => $student->intake->course->name,
                    "program" => $student->program->name,
                    "marks" => $marks,
                    "total" => $total,
                    "average" => $average,
                    "grade" => $grade,
                    "remark" => $remark,
                ];
            })->filter(fn($item) => $item->marks->count());

        if (request()->input('download')) {

            $name = sprintf('Transcripts-%s.pdf', time());

            $pdf = App::make('snappy.pdf.wrapper')
                ->setPaper('A4')
                ->setOption('no-outline', true)
                ->loadView('pdf.examinations.transcripts', ['transcripts' => $transcripts, 'term' => $term]);

            return $pdf->stream($name);
        }

        return Inertia::render('Examinations/Transcripts/Index', [
            'terms' => $terms,
            'term' => $term,
            'transcripts' => $transcripts,
            'search' => $search,
        ]);
    }
}
