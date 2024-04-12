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
        Schema::create('bog_members', function (Blueprint $table) {
            $table->id();
            $table->string('photo')->nullable();
            $table->integer('idno')->nullable();
            $table->enum('gender', ["Male", "Female"])->nullable();
            $table->boolean('plwd')->default(false);
            $table->string('surname')->nullable();
            $table->string('first_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('email')->nullable();
            $table->string('box_no')->nullable();
            $table->string('post_code')->nullable();
            $table->string('town')->nullable();
            $table->boolean('active')->default(true);
            $table->date('term_start_at')->nullable();
            $table->date('term_end_at')->nullable();
            $table->date('term_count')->nullable();
            $table->foreignId('bog_position_id');
            $table->timestamps();
            $table->foreign('bog_position_id')->references('id')->on('bog_positions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bog_members');
    }
};
