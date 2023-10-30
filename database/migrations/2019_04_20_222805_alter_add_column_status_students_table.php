<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAddColumnStatusStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('students', function (Blueprint $table) {
            if(!Schema::hasColumn('students','status'))
                $table->enum('status',['In session', 'On Attachment', 'Completed', 'Dropout'])->default('In session')->after('student_role_id');
        });
        // ALTER TABLE `students` ADD `status` ENUM('In session','On Attachment','Completed','Dropout') NOT NULL DEFAULT 'In session' AFTER `student_role_id`;
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('students', function (Blueprint $table) {
            if(Schema::hasColumn('students','status'))
                $table->dropColumn('status');
        });
    }
}
