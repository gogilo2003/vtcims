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
        Schema::create('guardians', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable()->default('');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->timestamps();
        });
        Schema::table('students', function (Blueprint $table) {
            $table->foreignId('guardian_id')->after('status')->nullable();
            $table->foreign('guardian_id')->references('id')->on('guardians');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropForeign(['guardian_id']);
            $table->dropColumn('guardian_id');
        });
        Schema::dropIfExists('guardians');
    }
};
