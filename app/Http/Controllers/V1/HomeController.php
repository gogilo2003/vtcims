<?php

namespace App\Http\Controllers\V1;

use Inertia\Inertia;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    function dashboard()
    {
        $students = [];
        foreach (Student::selectRaw('gender, count(*) as count')
            ->where('status', 'In Session')
            ->groupBy('gender')
            ->get()->map(fn ($item) => [
                ($item->gender ? 'female' : 'male') => $item->count
            ]) as $item) {

            if (isset($item['female'])) {
                $students['female'] = $item['female'];
            }
            if (isset($item['male'])) {
                $students['male'] = $item['male'];
            }
        }
        $students['total'] = $students['male'] + $students['female'];

        $students['all'] = Student::get()->count();

        return Inertia::render('Dashboard', ['students' => $students]);
    }
}
