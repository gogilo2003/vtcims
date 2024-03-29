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
        Schema::dropIfExists('attendances');
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('allocation_lesson_id');
            $table->dateTime('attendance_date');
            $table->timestamps();
            $table->foreign('allocation_lesson_id')->references('id')->on('allocation_lesson');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
