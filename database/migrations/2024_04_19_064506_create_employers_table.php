<?php

use App\Models\Employer;
use App\Models\Staff;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('employers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::table('staff', function (Blueprint $table) {
            $table->foreignId('employer_id')->after('employer')->nullable();
            $table->foreign('employer_id')->references('id')->on('employers');
        });

        foreach (Staff::select('employer')->whereNotNull('employer')->get()->pluck('employer')->unique()->values() as $key => $value) {
            $employer = new Employer();
            $employer->name = $value;
            $employer->save();
            $employer->refresh();
            Staff::where('employer', 'like', '%' . $value . '%')->get()->each(function (Staff $staff) use ($employer) {
                $staff->employer_id = $employer->id;
                $staff->save();
            });
        }

        Schema::table('staff', function (Blueprint $table) {
            $table->dropColumn('employer');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('staff', function (Blueprint $table) {
            $table->string('employer')->after('employer_id');
        });
        Staff::whereNotNull('employer_id')->get()->each(function (Staff $staff) {
            $staff->employer = Employer::find($staff->employer_id)->name;
            $staff->save();
        });
        Schema::table('staff', function (Blueprint $table) {
            $table->dropForeign(['employer_id']);
            $table->dropColumn('employer_id');
        });
        Schema::dropIfExists('employers');
    }
};
