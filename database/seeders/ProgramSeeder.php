<?php

namespace Database\Seeders;

use App\Models\Program;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProgramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $programs = [
            [
                "name" => "NITA",
                "description" => "National Industrial Training Authority",
            ],
            [
                "name" => "KNEC",
                "description" => "Kenya National Examination Council",
            ],
        ];

        foreach ($programs as $program) {
            $item = Program::where('name', $program)->first() ?? new Program();
            $item->name = $program['name'];
            $item->description = $program['description'];
            $item->save();
        }
    }
}
