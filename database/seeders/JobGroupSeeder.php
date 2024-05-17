<?php

namespace Database\Seeders;

use App\Models\JobGroup;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class JobGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jobGroups = [
            "R",
            "Q",
            "P",
            "N",
            "M",
            "L",
            "K",
            "J",
            "H",
            "G",
        ];

        foreach ($jobGroups as $jobGroup) {
            $item = JobGroup::where('name', $jobGroup)->first() ?? new JobGroup();
            $item->name = $jobGroup;
            $item->save();
        }
    }
}
