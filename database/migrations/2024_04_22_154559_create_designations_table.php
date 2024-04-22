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
        Schema::create('designations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });
        Schema::table('staff', function (Blueprint $table) {
            $table->foreignId('designation_id')->nullable()->after('job_group_id');
            $table->foreign('designation_id')->references('id')->on('designations');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('staff', function (Blueprint $table) {
            $table->dropForeign(['designation_id']);
            $table->dropColumn('designation_id');
        });
        Schema::dropIfExists('designations');
    }
};
