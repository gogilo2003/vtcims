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
        Schema::create('trade_areas', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->mediumText('description')->nullable();
            $table->timestamps();
        });

        Schema::table('staff', function (Blueprint $table) {
            $table->foreignId('trade_area_id')->after('staff_role_id')->nullable();
            $table->foreign('trade_area_id')->references('id')->on('trade_areas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('staff', function (Blueprint $table) {
            $table->dropForeign(['trade_area_id']);
            $table->dropColumn('trade_area_id');
        });

        Schema::dropIfExists('trade_areas');
    }
};
