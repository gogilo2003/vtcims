<?php

namespace Database\Seeders;

use App\Models\Sponsor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SponsorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sponsors = [
            "Self Sponsored",
        ];

        foreach ($sponsors as $sponsor) {
            $item = Sponsor::where('name', $sponsor)->first() ?? new Sponsor();
            $item->name = $sponsor;
            $item->save();
        }
    }
}
