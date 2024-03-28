<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

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

        \Illuminate\Support\Facades\DB::table('admins')->get()->each(function ($admin) {
            $role = \App\Models\Role::find($admin->admin_role_id);
            $admin_role = \Illuminate\Support\Facades\DB::table('admin_roles')->find($admin->admin_role_id);

            $is_admin = (\Illuminate\Support\Str::contains($admin_role->name, 'Super') || \Illuminate\Support\Str::contains($admin_role->name, 'Admin')) ? 1 : 0;

            $user = new \App\Models\User();
            $user->name = $admin->name;
            $user->email = $admin->email;
            $user->password = $admin->password;
            $user->is_admin = $is_admin;
            $role->users()->save($user);
            // \Illuminate\Support\Facades\DB::delete('delete admins where id = ?', $admin->id);
        });
        return 0;
    }
}
