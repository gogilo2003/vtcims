<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

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
        \Illuminate\Support\Facades\DB::table('admin_roles')->get()->each(function ($admin_role) {
            $user = new \App\Models\Role();
            $user->name = $admin_role->name;
            $user->permissions = $admin_role->details;
            $user->save();
            // \Illuminate\Support\Facades\DB::delete('delete admin_roles where id = ?', $admin_role->id);
        });
        $this->info('Copy Admin Roles Done');
    }
}
