<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeeTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fee_transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('particulars')->nullable();
            $table->integer('student_id')->unsigned();
            $table->foreign('student_id')->references('id')->on('students');
            $table->integer('fee_id')->unsigned();
            $table->foreign('fee_id')->references('id')->on('fees');
            $table->integer('fee_vote_head_id')->unsigned();
            $table->foreign('fee_vote_head_id')->references('id')->on('fee_vote_heads');
            $table->string('type');
            $table->decimal('amount',10,2);
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
        Schema::dropIfExists('fee_transactions');
    }
}
