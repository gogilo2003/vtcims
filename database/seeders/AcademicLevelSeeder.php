<?php

namespace Database\Seeders;

use App\Models\AcademicLevel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AcademicLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $academicLevels = [
            "Form 4",
            "Form 3",
            "Form 2",
            "Form 1",
            "Std 8",
            "Std 7",
            "Std 6",
            "Std 5",
            "Std 4",
            "Std 3",
            "Std 2",
            "Std 1",
        ];

        foreach ($academicLevels as $AcademicLevel) {
            $item = AcademicLevel::where('name', $AcademicLevel)->first() ?? new AcademicLevel();
            $item->name = $AcademicLevel;
            $item->save();
        }
    }
}
