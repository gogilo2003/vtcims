<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('staff', function (Blueprint $table) {
            $table->boolean('teach')->default(false)->after('staff_role_id');
            $table->foreignId('status_id')->nullable()->default(1)->after('staff_role_id');
            $table->foreign('status_id')->references('id')->on('staff_statuses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('staff', function (Blueprint $table) {
            $table->dropColumn('teach');
            $table->dropForeign(['status_id']);
            $table->dropColumn('status_id');
        });
    }
};
