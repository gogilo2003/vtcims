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
        Schema::create('allocation_lesson', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('allocation_id');
            $table->foreignId('lesson_id');
            $table->timestamps();
            $table->foreign('allocation_id')->references('id')->on('staff_subject');
            $table->foreign('lesson_id')->references('id')->on('lessons');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('allocation_lesson');
    }
};
