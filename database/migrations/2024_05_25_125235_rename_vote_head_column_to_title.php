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
        Schema::table('fee_vote_heads', function (Blueprint $table) {
            $table->renameColumn('vote_head', 'title');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fee_vote_heads', function (Blueprint $table) {
            $table->renameColumn('title', 'vote_head');
        });
    }
};