<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableStaffAddColumnAdminId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('staff', function (Blueprint $table) {
            if(!Schema::hasColumn('staff','admin_id')){
                $table->integer('admin_id')->unsigned()->nullable()->after('staff_role_id');
                $table->foreign('admin_id')->references('id')->on('admins');
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
            if(Schema::hasColumn('staff','admin_id')){
                $table->dropForeign(['admin_id']);
                $table->dropColumn('admin_id');
            }
        });
    }
}
