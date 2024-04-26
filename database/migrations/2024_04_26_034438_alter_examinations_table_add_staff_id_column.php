<?php

use App\Models\Allocation;
use App\Models\Examination;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('examinations', function (Blueprint $table) {
            $table->unsignedInteger('staff_id')->nullable()->after('title');
            $table->foreign('staff_id')->references('id')->on('staff')->onDelete('cascade');
        });

        // Examination::all()->each(function (Examination $examination) {
        //     $allocations = Allocation::with('intakes')->where('term_id', $examination->term_id)->where('subject_id', $examination->subject_id)->get();
        //     dump("Allocations: ", $allocations->count());
        //     $allocations->each(function (Allocation $allocation) {
        //         dump('Intakes:', $allocation->intakes->count());
        //     });
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('examinations', function (Blueprint $table) {
            $table->dropForeign(['staff_id']);
            $table->dropColumn('staff_id');
        });
    }
};
