<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();
        try {
            Schema::table('fee_vote_heads', function (Blueprint $table) {
                $table->dropPrimary('fee_vote_heads_id_primary');
            });
        } catch (Exception $exception) {
        }
        try {
            Schema::table('fee_vote_heads', function (Blueprint $table) {
                $table->dropColumn('id');
            });
            Schema::table('fee_vote_heads', function (Blueprint $table) {
                $table->id()->first();
            });
            Schema::table('fee_transactions', function (Blueprint $table) {
                $table->unsignedBigInteger('fee_vote_head_id')->nullable()->change();
            });
        } catch (Exception $exception) {
        }
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::table('fee_transactions', function (Blueprint $table) {
            $table->unsignedInteger('fee_vote_head_id')->change();
        });
        try {
            Schema::table('fee_vote_heads', function (Blueprint $table) {
                $table->dropPrimary('fee_vote_heads_id_primary');
            });
        } catch (Exception $exception) {
        }
        try {
            Schema::table('fee_vote_heads', function (Blueprint $table) {
                $table->dropColumn('id');
            });
        } catch (Exception $exception) {
        }
        Schema::table('fee_vote_heads', function (Blueprint $table) {
            $table->increments('id')->first();
        });
        Schema::enableForeignKeyConstraints();
    }
};
