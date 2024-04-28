<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

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
            $item = new \App\Models\JobGroup();
            $item->name = $jobGroup;
            $item->save();
        }
    }
}
