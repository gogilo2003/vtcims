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
        Schema::table('fee_transaction_types', function (Blueprint $table) {
            $table->boolean('income')->nullable()->default(null)->after('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fee_transaction_types', function (Blueprint $table) {
            $table->dropColumn('income');
        });
    }
};
