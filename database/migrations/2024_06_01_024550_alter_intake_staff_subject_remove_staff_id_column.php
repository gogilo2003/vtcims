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
        if (Schema::hasColumn('intake_staff_subject', 'staff_id')) {
            Schema::table('intake_staff_subject', function (Blueprint $table) {
                $table->dropForeign(['staff_id']);
                $table->dropColumn('staff_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('intake_staff_subject', function (Blueprint $table) {
            $table->unsignedInteger('staff_id');
            $table->foreign('staff_id')->references('id')->on('staffs');
        });
    }
};
