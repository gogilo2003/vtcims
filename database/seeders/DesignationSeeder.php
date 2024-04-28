<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DesignationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $designations = [
            "Chief Principal Instructor",
            "Principal Instructor",
            "Senior Instructor",
            "Instructor I",
            "Instructor II",
            "Instructor III",
        ];

        foreach ($designations as $designation) {
            $item = new \App\Models\Designation();
            $item->name = $designation;
            $item->save();
        }
    }
}
