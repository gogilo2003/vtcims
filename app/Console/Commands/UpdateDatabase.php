<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class UpdateDatabase extends Command
{
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
        Artisan::call('migrate');
        Artisan::call('vtcims:copy-roles');
        Artisan::call('vtcims:copy-users');
        Artisan::call('vtcims:clean-tables');
    }
}
