<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExaminationIntakeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('examination_intake', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('intake_id')->unsigned();
            $table->foreign('intake_id')->references('id')->on('intakes');
            $table->integer('examination_id')->unsigned();
            $table->foreign('examination_id')->references('id')->on('examinations');
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
        Schema::dropIfExists('examination_intake');
    }
}
