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
        Schema::create('job_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });
        Schema::table('staff', function (Blueprint $table) {
            $table->foreignId('job_group_id')->nullable()->after('employer_id');
            $table->foreign('job_group_id')->references('id')->on('job_groups');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('staff', function (Blueprint $table) {
            $table->dropForeign(['job_group_id']);
            $table->dropColumn('job_group_id');
        });
        Schema::dropIfExists('job_groups');
    }
};
