<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        \App\Models\User::factory()->create([
            'name' => 'Ogilo George',
            'email' => 'gogilo2003@hotmail.com',
            'password' => bcrypt('Pablo!2013'),
        ]);

        // $this->call([
        //     LessonSeeder::class
        // ]);
    }
}
