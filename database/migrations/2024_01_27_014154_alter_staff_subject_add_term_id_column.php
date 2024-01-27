<?php

use App\Models\StaffSubject;
use App\Models\Term;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('staff_subject', function (Blueprint $table) {
            $table->unsignedInteger('term_id')->nullable()->after('id');
            $table->foreign('term_id')->references('id')->on('terms')->onDelete('set null');
        });
        foreach (StaffSubject::all() as $staff_subject) {
            $term = Term::orderBy('start_date', 'ASC')
                ->where('start_date', '<=', $staff_subject->created_at)
                ->whereDate('end_date', '>=', $staff_subject->created_at)
                ->first();
            if ($term) {
                $staff_subject->term_id = $term->id;
                $staff_subject->save();
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('staff_subject', function (Blueprint $table) {
            $table->dropForeign(['term_id']);
            $table->dropColumn('term_id');
        });
    }
};
