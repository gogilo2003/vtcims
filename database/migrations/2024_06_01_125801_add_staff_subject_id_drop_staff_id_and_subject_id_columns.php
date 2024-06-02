<?php

use App\Models\StaffSubject;
use Illuminate\Support\Carbon;
use App\Models\IntakeStaffSubject;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();
        if (DB::table('staff_subject')->count() == 0) {
            if (Schema::hasColumn('intake_staff_subject', 'staff_id')) {
                foreach (DB::table('intake_staff_subject')->distinct()->get(['staff_id', 'subject_id']) as $row) {
                    $values = DB::table('intake_staff_subject')
                        ->where('staff_id', $row->staff_id)
                        ->where('subject_id', $row->subject_id)
                        ->get()
                        ->groupBy(function ($val) {
                            return Carbon::parse($val->created_at)->format('d');
                        });

                    foreach ($values as $value) {
                        $intake = (object) $value->first();

                        DB::table('staff_subject')->insert([
                            'staff_id' => $intake->staff_id,
                            'subject_id' => $intake->subject_id,
                            'created_at' => $intake->created_at,
                            'updated_at' => $intake->created_at,
                        ]);
                    }
                }
            }

        }
        if (!Schema::hasColumn('intake_staff_subject', 'staff_subject_id')) {
            Schema::table('intake_staff_subject', function (Blueprint $table) {
                $table->unsignedInteger('staff_subject_id')->after('id')->nullable();
                $table->foreign('staff_subject_id')->references('id')->on('staff_subject')->onDelete('cascade');
            });
        }


        // Update the new staff_subject_id field with the id from staff_subject table
        foreach (StaffSubject::all() as $item) {

            $allocations = IntakeStaffSubject::where('staff_id', $item->staff_id)
                ->where('subject_id', $item->subject_id)
                ->get();

            foreach ($allocations as $allocation) {
                $allocation->staff_subject_id = $item->id;
                $allocation->save();
            }
        }
        try {
            Schema::table('intake_staff_subject', function (Blueprint $table) {
                $table->dropForeign(['staff_id']);
                $table->dropForeign(['subject_id']);
                $table->dropColumn('staff_id');
                $table->dropColumn('subject_id');
            });
        } catch (\Throwable $th) {
            //throw $th;
        }
        try {
            Schema::table('intake_staff_subject', function (Blueprint $table) {
                $table->dropForeign(['staff_id']);
            });
        } catch (\Throwable $th) {
            //throw $th;
        }
        try {
            Schema::table('intake_staff_subject', function (Blueprint $table) {
                $table->dropForeign(['subject_id']);
            });
        } catch (\Throwable $th) {
            //throw $th;
        }
        try {
            Schema::table('intake_staff_subject', function (Blueprint $table) {
                $table->dropColumn('staff_id');
            });
        } catch (\Throwable $th) {
            //throw $th;
        }
        try {
            Schema::table('intake_staff_subject', function (Blueprint $table) {
                $table->dropColumn('subject_id');
            });
        } catch (\Throwable $th) {
            //throw $th;
        }

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
