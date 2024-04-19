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
        Schema::table('staff', function (Blueprint $table) {
            $table->dropForeign('staff_admin_id_foreign');
            $table->dropColumn('admin_id');
            $table->foreignId('user_id')->nullable()->after('teach');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('staff', function (Blueprint $table) {
            $table->dropForeign('staff_user_id_foreign');
            $table->dropColumn('user_id');
            $table->unsignedInteger('admin_id')->nullable()->after('teach');
            $table->foreign('admin_id')->references('id')->on('admins');
        });
    }
};
