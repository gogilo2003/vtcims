<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterStaffTableAddStatusColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $status = DB::table('staff_statuses')->where('name', 'current')->first();

        if (is_null($status)) {
            DB::table('staff_statuses')->insert([
                'name' => 'current',
                'description' => 'Staff currently stationed at ' . config('app.name')
            ]);
        }
        Schema::table('staff', function (Blueprint $table) {
            if (!Schema::hasColumn('staff', 'status')) {
                $table->unsignedBigInteger('staff_status_id')->after('gender')->default(1);
                $table->foreign('staff_status_id')->references('id')->on('staff_statuses')->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('staff', function (Blueprint $table) {
            if (Schema::hasColumn('staff', 'staff_status_id')) {
                $table->dropForeign(['staff_status_id']);
                $table->dropColumn('staff_status_id');
            }
        });
    }
}
