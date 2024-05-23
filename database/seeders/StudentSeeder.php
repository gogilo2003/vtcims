<?php

namespace Database\Seeders;

use App\Imports\StudentsImport;
use Illuminate\Database\Seeder;
use Maatwebsite\Excel\Facades\Excel;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Excel::import(new StudentsImport(), storage_path('app/imports/studentsData.csv'));
    }
}
