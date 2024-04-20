<?php

namespace App\Console\Commands;

use App\Models\Staff;
use App\Models\Student;
use Illuminate\Support\Str;
use Illuminate\Console\Command;

class CorrectPhotoPaths extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vtcims:photo-paths';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Correct Photo Paths';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $students = Student::whereNotNull('photo')->get();
        $staff = Staff::whereNotNull('photo')->get();

        $count = 0;

        foreach ($students as $student) {
            if (!Str::startsWith($student->photo, 'students/')) {
                $this->info($student->photo);
                $student->photo = 'students/' . $student->photo;
                $student->save();
                $count += 1;
            }
        }
        $this->info('Updated ' . $count . ' students');

        $count = 0;
        foreach ($staff as $member) {
            if (!Str::startsWith($member->photo, 'staff_members/')) {
                $member->photo = 'staff_members/' . $member->photo;
                $member->save();
                $count += 1;
            }
        }
        $this->info('Updated ' . $count . ' staff members');
    }
}
