<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeeTransactionDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fee_transaction_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('fee_transaction_id')->unsigned();
            $table->integer('fee_vote_head_id')->unsigned();
            $table->decimal('amount', 10, 2);
            $table->timestamps();
            $table->foreign('fee_transaction_id')->references('id')->on('fee_transactions')->onDelete('cascade');
            $table->foreign('fee_vote_head_id')->references('id')->on('fee_vote_heads')->onDelete('cascade');
        });

        try {
            Schema::table('fee_transactions', function (Blueprint $table) {
                $table->dropForeign(['fee_vote_head_id']);
                $table->dropColumn('fee_vote_head_id');
            });
        } catch (Exception $e) {
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fee_transactions', function (Blueprint $table) {
            $table->integer('fee_vote_head_id')->unsigned();
            $table->foreign('fee_vote_head_id')->references('id')->on('fee_vote_heads');
        });

        Schema::dropIfExists('fee_transaction_details');
    }
}
