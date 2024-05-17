<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CopyRoles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vtcims:copy-roles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Copy Admin Roles to Roles';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (Schema::hasTable('admin_roles')) {
            if (DB::table('users')->count() === 0) {
                DB::table('admin_roles')->get()->each(function ($admin_role) {
                    $user = new \App\Models\Role();
                    $user->name = $admin_role->name;
                    $user->permissions = $admin_role->details;
                    $user->save();
                });
                $this->info('Copy Admin Roles Done');
            } else {
                $this->info('Users table already has data. Skipping...');
            }
        }
        return 0;
    }
}
