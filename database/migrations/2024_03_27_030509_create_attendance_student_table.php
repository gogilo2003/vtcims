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
        // Schema::disableForeignKeyConstraints();

        // Drop foreign keys
        Schema::table("examination_results", function (Blueprint $table) {
            $table->dropForeign(["student_id"]);
        });

        Schema::table("fee_transactions", function (Blueprint $table) {
            $table->dropForeign(["student_id"]);
        });

        Schema::table("leave_outs", function (Blueprint $table) {
            $table->dropForeign(["student_id"]);
        });

        // Change data types
        Schema::table("students", function (Blueprint $table) {
            $table->unsignedBigInteger("id")->change();
        });

        Schema::table("examination_results", function (Blueprint $table) {
            $table->unsignedBigInteger("student_id")->change();
        });

        Schema::table("fee_transactions", function (Blueprint $table) {
            $table->unsignedBigInteger("student_id")->change();
        });

        Schema::table("leave_outs", function (Blueprint $table) {
            $table->unsignedBigInteger("student_id")->change();
        });

        // Add foreign keys
        Schema::table("examination_results", function (Blueprint $table) {
            $table->foreign('student_id')->references('id')->on('students');
        });

        Schema::table("fee_transactions", function (Blueprint $table) {
            $table->foreign('student_id')->references('id')->on('students');
        });

        Schema::table("leave_outs", function (Blueprint $table) {
            $table->foreign('student_id')->references('id')->on('students');
        });

        // Schema::enableForeignKeyConstraints();

        Schema::create('attendance_student', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attendance_id');
            $table->foreignId('student_id');
            $table->timestamps();
            $table->foreign('attendance_id')->references('id')->on('attendances')->onDelete('cascade');
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_student');
    }
};
