<?php

use App\Models\User;
use App\Models\Admin;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Admin::all()->each(function (Admin $admin) {
        //     if (!User::where("email", $admin->email)->exists()) {
        //         $user = new User();
        //         $user->name = $admin->name;
        //         $user->email = $admin->email;
        //         $user->password = $admin->password;
        //         $user->save();
        //     }
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Schema::table('users', function (Blueprint $table) {
        //     $table->truncate();
        // });
    }
};
