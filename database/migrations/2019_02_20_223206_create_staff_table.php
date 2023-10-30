<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStaffTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staff', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('idno')->unique();
            $table->integer('pfno')->unique()->nullable();
            $table->integer('manno')->unique()->nullable();
            $table->string('photo')->nullable();
            $table->string('surname');
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('box_no')->nullable();
            $table->string('post_code')->nullable();
            $table->string('town')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('employer')->nullable();
            $table->string('gender')->nullable();
            $table->integer('staff_role_id')->unsigned();
            $table->foreign('staff_role_id')->references('id')->on('staff_roles');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('staff');
    }
}
