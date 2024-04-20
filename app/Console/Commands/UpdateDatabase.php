<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class UpdateDatabase extends Command
{
    protected $output = "";
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vtcims:update-database';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update database structure and re align data';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting migration...');
        Artisan::call('migrate', ["--seed" => true], $this->output);
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

        return 0;
    }
}
