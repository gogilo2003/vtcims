<?php

use App\Models\Student;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        try {
            Schema::disableForeignKeyConstraints();

            Schema::table('students', function (Blueprint $table) {
                $table->dropPrimary();
            });

            Schema::table('students', function (Blueprint $table) {
                $table->bigIncrements('id')->change();
            });

            Schema::enableForeignKeyConstraints();
        } catch (\Throwable $th) {

        }

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            //
        });
    }
};
