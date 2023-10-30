<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterExaminationTestsTableAddSessionColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('examination_tests', function (Blueprint $table) {
            $table->integer('session')->unsigned()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('examination_tests', function (Blueprint $table) {
            if(Schema::hasColumn('examination_tests','session'))
                $table->dropColumn('session');
        });
    }
}
