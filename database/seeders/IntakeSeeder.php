<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Intake;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class IntakeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $startYear = env('START_YEAR', 2022);
        $endYear = date('Y');
        $intakeMonths = explode(",", strtoupper(strtolower(env('INTAKE_MONTHS', 'Jan,May,Sep'))));

        $courses = Course::all();

        foreach (range($startYear, $endYear) as $year) {
            foreach ($intakeMonths as $intakeMonth) {
                foreach ($courses as $course) {
                    $intake = new Intake();
                    $intake->course_id = $course->id;
                    $intake->staff_id = $course->staff_id;
                    $intake->name = sprintf(
                        "%s/%d/%s",
                        $course->code,
                        $year,
                        $intakeMonth
                    );
                    $intake->start_date = Carbon::parse(
                        sprintf(
                            "%d-%s-01",
                            $year,
                            $intakeMonth
                        )
                    );
                    $intake->end_date = Carbon::parse(
                        sprintf(
                            "%d-%s-01",
                            $year,
                            $intakeMonth
                        )
                    )->addMonths(
                            trim(
                                str_replace(
                                    "months",
                                    "",
                                    $course->duration
                                )
                            )
                        );
                    $intake->save();
                }
            }
        }
    }
}
