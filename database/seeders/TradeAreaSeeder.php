<?php

namespace Database\Seeders;

use App\Models\TradeArea;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TradeAreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tradeAreas = collect([
            [
                "name" => "Plumbing",
                "description" => "",
            ],
            [
                "name" => "Information and Communication Technology",
                "description" => "",
            ],
            [
                "name" => "Electrical",
                "description" => "",
            ],
            [
                "name" => "Food and Beverage",
                "description" => "",
            ],
            [
                "name" => "Building/Masonry",
                "description" => "",
            ],
            [
                "name" => "Hairdressing and Beauty Therapy",
                "description" => "",
            ],
            [
                "name" => "Fashion Design",
                "description" => "",
            ],
        ])->sortBy('name')->values()->all();

        foreach ($tradeAreas as $tradeArea) {
            $item = TradeArea::where('name', $tradeArea)->first() ?? new TradeArea();
            $item->name = $tradeArea['name'];
            $item->description = $tradeArea['description'];
            $item->save();
        }
    }
}
