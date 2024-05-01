<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

class UpdateDatabase extends Command
{
    protected $output = "";
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vtcims:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update database structure and re align data to recent upgrades';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting migration...');
        Artisan::call('migrate', [], $this->output);
        $this->info('Migration complete.');

        $this->info('Copying roles...');
        Artisan::call('vtcims:copy-roles', [], $this->output);
        $this->info('Roles copied.');

        $this->info('Copying users...');
        Artisan::call('vtcims:copy-users', [], $this->output);
        $this->info('Users copied.');

        $this->info('Cleaning tables...');
        Artisan::call('vtcims:clean-tables', [], $this->output);
        $this->info('Tables cleaned.');

        Artisan::call('vtcims:photo-paths', [], $this->output);
        $this->info('Photo Paths updated.');

        if (DB::table('fee_transaction_types')->count() === 0) {
            $this->call('db:seed', ['--class' => 'TransactionTypesSeeder']);
            $this->info('Transaction types seeded successfully.');
        } else {
            $this->info('Transaction types table already has data. Skipping seeder.');
        }

        if (DB::table('lessons')->count() === 0) {
            $this->call('db:seed', ['--class' => 'LessonSeeder']);
            $this->info('Lessons seeded successfully.');
        } else {
            $this->info('Lessons table already has data. Skipping seeder.');
        }

        if (DB::table('job_groups')->count() === 0) {
            $this->call('db:seed', ['--class' => 'JobGroupSeeder']);
            $this->info('Job Groups seeded successfully.');
        } else {
            $this->info('Job Groups table already has data. Skipping seeder.');
        }

        if (DB::table('designations')->count() === 0) {
            $this->call('db:seed', ['--class' => 'DesignationSeeder']);
            $this->info('Designations seeded successfully.');
        } else {
            $this->info('Designations table already has data. Skipping seeder.');
        }

        return 0;
    }
}
