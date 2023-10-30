<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeeVoteHeadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fee_vote_heads', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('fee_id')->unsigned();
            $table->string('vote_head');
            $table->decimal('share', 10, 2);
            $table->timestamps();
            $table->foreign('fee_id')->references('id')->on('fees');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fee_vote_heads');
    }
}
