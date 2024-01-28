<?php

namespace App\Http\Controllers\V1;

use Inertia\Inertia;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    function dashboard()
    {
        $overallStudents = [];
        foreach (Student::selectRaw('gender, count(*) as count')
            ->where('status', 'In Session')
            ->groupBy('gender')
            ->get()->map(fn ($item) => [
                ($item->gender ? 'female' : 'male') => $item->count
            ]) as $item) {

            if (isset($item['female'])) {
                $overallStudents['female'] = $item['female'];
            }
            if (isset($item['male'])) {
                $overallStudents['male'] = $item['male'];
            }
        }
        $overallStudents['total'] = $overallStudents['male'] + $overallStudents['female'];

        $overallStudents['all'] = Student::get()->count();

        // Current Enrollment Stats
        $labels = ['Male', 'Female'];

        $male = Student::selectRaw('count(*) as count')->where('status', 'In session')->where('gender', 0)->first();
        $female = Student::selectRaw('count(*) as count')->where('status', 'In session')->where('gender', 1)->first();

        $data = [$male->count, $female->count];
        $current = [
            'labels' => $labels,
            'data' => $data,
        ];

        // General Enrollment by Status
        $labels = ['In session', 'On Attachment', 'Completed', 'Dropout'];
        $data = [];

        foreach ($labels as $key => $value) {
            $data[] = Student::selectRaw('count(*) as count')->where('status', $value)->first()->count;
        }
        $status = [
            'labels' => $labels,
            'data' => $data,
        ];

        // General Annual Enrolment
        $dates = DB::table('students')->select(DB::raw('min(date_of_admission) as start, max(date_of_admission) as end'))->get()->first();

        $begin = date_create($dates->start);
        $end = date_create($dates->end);

        $interval = new \DateInterval('P1Y');
        $daterange = new \DatePeriod($begin, $interval, $end);
        $labels = [];

        // rsort($daterange);

        foreach ($daterange as $key => $date) {
            $labels[] = $date->format("Y");
            // if ($key===2)
            //     break;
        }

        $datasets = [];
        $genders = ["Male", "Female"];
        $colors = ['orange', 'purple', 'yellow', 'beige', 'red'];

        $labels = array_slice($labels, -5);

        foreach ($genders as $key => $gender) {
            $data = [];
            foreach ($labels as $date) {
                $students = DB::table('students')
                    ->select(DB::raw('count(*) as enrolment'))
                    ->where('gender', $key)
                    ->whereYear('date_of_admission', $date)
                    ->first();

                $data[] = $students->enrolment;
            }

            $data[] = 0;
            $datasets[] = [
                'label' => $gender,
                'data' => $data,
                'backgroundColor' => $colors[$key],
                'borderColor' => $colors[$key],
                // 'pointBackgroundColor'=>'#fff',
                // 'pointBorderColor'=>'#fff'
            ];
        }

        $annual = [
            'labels' => $labels,
            'datasets' => $datasets,
        ];

        return Inertia::render('Dashboard', [
            'students' => $overallStudents,
            'current' => $current,
            'status' => $status,
            'annual' => $annual,
        ]);
    }
}
