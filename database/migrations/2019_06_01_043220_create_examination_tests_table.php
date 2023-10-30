<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExaminationTestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('examination_tests', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->integer('examination_id')->unsigned();
            $table->foreign('examination_id')->references('id')->on('examinations');
            $table->decimal('outof',10,2);
            $table->date('taken_on');
            $table->mediumText('notes')->nullable();
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
        Schema::dropIfExists('examination_tests');
    }
}
