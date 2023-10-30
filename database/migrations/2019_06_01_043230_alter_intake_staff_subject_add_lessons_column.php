<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterIntakeStaffSubjectAddLessonsColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('intake_staff_subject', function (Blueprint $table) {
            $table->integer('lessons')->unsigned()->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('intake_staff_subject', function (Blueprint $table) {
            if(Schema::hasColumn('intake_staff_subject','lessons'))
                $table->dropColumn('lessons');
        });
    }
}
