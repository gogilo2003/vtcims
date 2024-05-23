<?php

namespace Database\Seeders;

use App\Models\StudentRole;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StudentRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $studentRoles = [
            [
                "name" => "Student",
                "description" => "Regular Student",
            ],
            [
                "name" => "Class Representative",
                "description" => "Class representative",
            ]
        ];

        foreach ($studentRoles as $studentRole) {
            $item = StudentRole::where('name', $studentRole)->first() ?? new StudentRole();
            $item->name = $studentRole['name'];
            $item->description = $studentRole['description'];
            $item->save();
        }
    }
}
