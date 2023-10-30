<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->increments('id');
            $table->string('photo')->nullable();
            $table->string('surname');
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('box_no')->nullable();
            $table->string('post_code')->nullable();
            $table->string('town')->nullable();
            $table->string('physical_address')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('birth_cert_no')->nullable();
            $table->integer('idno')->nullable();
            $table->boolean('gender')->nullable();
            $table->date('date_of_admission')->useCurrent();
            $table->integer('intake_id')->unsigned();
            $table->foreign('intake_id')->references('id')->on('intakes');
            $table->integer('program_id')->unsigned();
            $table->foreign('program_id')->references('id')->on('programs');
            $table->integer('sponsor_id')->unsigned();
            $table->foreign('sponsor_id')->references('id')->on('sponsors');
            $table->integer('student_role_id')->unsigned();
            $table->foreign('student_role_id')->references('id')->on('student_roles');
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
        Schema::dropIfExists('students');
    }
}
