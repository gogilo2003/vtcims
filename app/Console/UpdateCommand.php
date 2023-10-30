<?php

namespace App\Console;

use Illuminate\Console\Command;

use Storage;

class UpdateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'eschool:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Post update command';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        /**
         * Update staff table
         * update the newly added admin_id collumn inthe staff table with correct id
         */
        $this->comment('Updating staff new columns');
        $admins = \App\Models\Admin::all();
        foreach ($admins as $admin) {
            if ($staff = \App\Models\Staff::where('email', $admin->email)->first()) {
                $staff->admin_id = $admin->id;
                $staff->save();
            }
        }

        /**
         * Update examination_group_student pivot table
         */
        $this->comment('Updating Examination Group Students');
        $groups = \App\Models\ExaminationGroup::all();
        foreach ($groups as $group) {
            if ($group->students->count() == 0) {
                foreach ($group->intakes as $intake) {
                    $students = $intake->students->pluck('id')->toArray();
                    $group->students()->attach($students);
                }
            }
        }
    }
}
