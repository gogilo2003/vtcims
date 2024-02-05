<?php

namespace Database\Seeders;

use App\Models\Lesson;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class LessonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $days = collect([
            'Monday',
            'Tuesday',
            'Wednesday',
            'Thursday',
            'Friday',
        ]);

        $lessons = collect([
            (object)[
                'title' => 'Lesson 1',
                'start_at' => new \DateTime('8:15 AM'),
                'end_at' => new \DateTime('10:15 AM'),
            ],
            (object)[
                'title' => 'Lesson 2',
                'start_at' => new \DateTime('10:45 AM'),
                'end_at' => new \DateTime('12:45 PM'),
            ],
            (object)[
                'title' => 'Lesson 3',
                'start_at' => new \DateTime('02:00 PM'),
                'end_at' => new \DateTime('04:00 PM'),
            ],
        ]);

        foreach ($days as $day) {
            foreach ($lessons as $lesson) {
                $item = new Lesson();
                $item->title = sprintf('%s - %s', Str::substr($day, 0, 3), $lesson->title);
                $item->day = $day;
                $item->start_at = $lesson->start_at;
                $item->end_at = $lesson->end_at;
                $item->save();
            }
        }
    }
}
