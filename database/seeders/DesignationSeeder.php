<?php

namespace Database\Seeders;

use App\Models\Designation;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

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
            $item = Designation::where('name', $designation)->first() ?? new Designation();
            $item->name = $designation;
            $item->save();
        }
    }
}
