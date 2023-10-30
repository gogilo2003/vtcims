<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIntakeStaffSubjectTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('intake_staff_subject', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('intake_id')->unsigned();
            $table->foreign('intake_id')->references('id')->on('intakes');
            $table->integer('staff_id')->unsigned();
            $table->foreign('staff_id')->references('id')->on('staff');
            $table->integer('subject_id')->unsigned();
            $table->foreign('subject_id')->references('id')->on('subjects');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('intake_staff_subject');
    }
}
