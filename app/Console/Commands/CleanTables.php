<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;

class CleanTables extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vtcims:clean-tables';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove tables that are irrelevant to VTCIMS that belongs to admin-md CMS';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tables = [
            // "admin_roles",
            // "admins",
            "article_categories",
            "article_category_page",
            "articles",
            "authors",
            "comment_users",
            "comments",
            // "course_subject",
            // "courses",
            // "departments",
            "element_categories",
            "element_category_page",
            "elements",
            "event_categories",
            "event_category_page",
            "event_days",
            "event_event_speaker",
            "event_schedule_event_speaker",
            "event_schedules",
            "event_speakers",
            "events",
            // "examination_intake",
            // "examination_results",
            // "examination_tests",
            // "examinations",
            // "failed_jobs",
            // "fee_transaction_details",
            // "fee_transactions",
            // "fee_vote_heads",
            // "fees",
            "file_categories",
            "file_category_page",
            "files",
            // "grades",
            "guests",
            "hits",
            // "intake_staff_subject",
            // "intakes",
            // "leave_outs",
            "links",
            "menus",
            // "migrations",
            "package_page",
            "package_pictures",
            "packages",
            "page_picture_category",
            "page_profile",
            "page_project_category",
            "page_sermon",
            "page_video_category",
            "pages",
            // "password_reset_tokens",
            // "password_resets",
            // "personal_access_tokens",
            "picture_categories",
            "pictures",
            "profiles",
            // "programs",
            "project_categories",
            "projects",
            // "remarks",
            // "role_user",
            // "roles",
            "sermons",
            // "sponsors",
            // "staff",
            // "staff_roles",
            // "staff_statuses",
            // "staff_subject",
            // "student_roles",
            // "students",
            // "subjects",
            // "terms",
            // "users",
            "video_categories",
            "videos",
        ];

        Schema::disableForeignKeyConstraints();

        foreach ($tables as $table) {
            Schema::dropIfExists($table);
        }

        Schema::enableForeignKeyConstraints();
        return 0;
    }
}
