<?php

namespace App\Console\Commands;

use App\Models\Student;
use Illuminate\Support\Carbon;
use Illuminate\Console\Command;

class UpdateStudentStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vtcims:update-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update student status based on intake end date';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Get current date
        $currentDate = Carbon::now();

        // Get students in session
        $studentsInSession = Student::where('status', 'In Session')->get();

        foreach ($studentsInSession as $student) {
            // Get student's intake end date
            $endDate = Carbon::parse($student->intake->end_date);

            // Get internship duration from associated course

            $internshipDuration = $student->intake->course->internship_duration;

            // Calculate the end date considering the internship duration
            $endDateWithInternship = $endDate->addMonths($internshipDuration);

            // Check if the end date has passed
            if ($currentDate->gt($endDateWithInternship)) {
                // If end date has passed, set status to 'Completed'
                $student->status = 'Completed';
                $student->save();
            } elseif ($currentDate->gt($endDate)) {
                // If end date has passed but still within internship duration, set status to 'On Attachment'
                $student->status = 'On Attachment';
                $student->save();
            }
        }

        $this->info('Student statuses updated successfully.');
    }
}
