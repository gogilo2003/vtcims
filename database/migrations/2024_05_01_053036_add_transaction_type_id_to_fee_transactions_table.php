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
        Schema::table('fee_transactions', function (Blueprint $table) {
            $table->foreignId('transaction_type_id')->after('fee_id')->constrained('fee_transaction_types');
            $table->string('mode')->nullable()->after('transaction_type_id');
            $table->dropColumn('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fee_transactions', function (Blueprint $table) {
            $table->dropForeign(['transaction_type_id']);
            $table->dropColumn('transaction_type_id');
            $table->dropColumn('mode');
            $table->string('type')->nullable();
        });
    }
};
