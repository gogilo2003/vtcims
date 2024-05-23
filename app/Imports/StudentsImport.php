<?php

namespace App\Imports;

use App\Models\AcademicLevel;
use App\Support\Util;
use App\Models\Intake;
use App\Models\Student;
use App\Models\Guardian;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StudentsImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $arName = explode(" ", Str::title(Str::lower($row['student_name'])));
        $firstName = $arName[0];
        $surname = $arName[count($arName) - 1];
        $middleName = null;

        if (count($arName) > 2) {
            $middleName = implode(" ", array_slice($arName, 1, count($arName) - 2));
        }

        $intake = Intake::where('name', 'like', '%' . $row['intake'] . '%')->first();

        $gender = $row['gender'] ? (trim(Str::lower($row['gender'])) == 'female' ? 1 : 0) : 0;

        $parentName = trim(
            Str::title(
                Str::lower(
                    sprintf(
                        "%s%s",
                        $row['parents_name'],
                        $row['parents_name'] && $row['alternative_name']
                        ? "/" . $row['alternative_name']
                        : $row['alternative_name']
                    )
                )
            )
        );
        $parentContact = trim(
            Str::title(
                Str::lower(
                    sprintf(
                        "%s%s",
                        $row['parents_contact'],
                        $row['parents_contact'] && $row['alternative']
                        ? "," . $row['alternative']
                        : $row['alternative']
                    )
                )
            )
        );

        $guardianId = null;

        if ($parentName) {
            $guardian = new Guardian();
            $guardian->name = $parentName;
            $guardian->phone = Util::formatPhoneNumber($parentContact);
            $guardian->save();

            $guardianId = $guardian->id;
        }

        $plwd = $row['plwd'] ? (Str::lower($row['plwd']) == 'yes' ? 1 : 0) : 0;

        $academicLevelId = null;

        if ($row['academic_level']) {
            $academicLevel = AcademicLevel::where('name', 'like', '%' . $row['academic_level'] . '%')->first();
            $academicLevelId = $academicLevel ? $academicLevel->id : null;
        }

        $status = $intake->end_date > now() ? 'In Session' : 'Completed';

        $student = [
            "old_admission_no" => $row['admission_number'],
            "first_name" => $firstName,
            "middle_name" => $middleName,
            "surname" => $surname,
            "date_of_admission" => $row['date_of_admission'],
            "phone" => Util::formatPhoneNumber($row['student_contact']),
            "idno" => $row['idno'],
            "intake_id" => $intake->id,
            "gender" => $gender,
            "academic_level_id" => $academicLevelId,
            "guardian_id" => $guardianId,
            "sponsor_id" => 1,
            "program_id" => 1,
            "student_role_id" => 1,
            "status" => $status,
            "plwd" => $plwd,
        ];

        return new Student($student);
    }
}
