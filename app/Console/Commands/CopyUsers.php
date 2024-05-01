<?php

namespace App\Console\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CopyUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vtcims:copy-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Copy users from admin to users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (DB::table('users')->count() === 0) {
            DB::table('admins')->get()->each(function ($admin) {
                $role = \App\Models\Role::find($admin->admin_role_id);
                $admin_role = DB::table('admin_roles')->find($admin->admin_role_id);

                $is_admin = (Str::contains($admin_role->name, 'Super') || Str::contains($admin_role->name, 'Admin')) ? 1 : 0;

                $user = new \App\Models\User();
                $user->name = $admin->name;
                $user->email = $admin->email;
                $user->password = $admin->password;
                $user->is_admin = $is_admin;
                $role->users()->save($user);
            });
        } else {
            $this->info('Users table already has data. Skipping...');
        }
        return 0;
    }
}
