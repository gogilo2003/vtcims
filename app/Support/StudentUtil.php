<?php
namespace App\Support;

use App\Models\Student;

class StudentUtil
{
    static function prepAdmissionNo(Student $student): string
    {
        $admissionNo = $student->intake
            ? strtoupper(
                $student->intake->course->code . '/'
                . str_pad($student->id, 4, '0', 0) . '/'
                . $student->date_of_admission->format('Y')
            )
            : '';

        return $admissionNo;
    }
}
