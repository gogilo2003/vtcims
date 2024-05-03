<?php

use App\Models\FeeTransaction;
use App\Models\FeeTransactionMode;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('fee_transaction_modes', function (Blueprint $table) {
            $table->id();
            $table->string('name')->default('cash');
            $table->timestamps();
        });

        $modes = ["System", "Cash", "Cheque", "Bank Deposit", "Mobile Wallet"];

        foreach ($modes as $mode) {
            $txMode = new FeeTransactionMode();
            $txMode->name = $mode;
            $txMode->save();
        }
        Schema::table('fee_transactions', function (Blueprint $table) {
            $table->foreignId('transaction_mode_id')->nullable()->after('mode');
            $table->foreign('transaction_mode_id')->references('id')->on('fee_transaction_modes');
        });
        foreach (FeeTransaction::all() as $tx) {
            $mode = FeeTransactionMode::where('name', 'like', '%' . $tx->mode . '%')->first();
            $tx->transaction_mode_id = $mode->id;
            $tx->save();
        }
        Schema::table('fee_transactions', function (Blueprint $table) {
            $table->dropColumn('mode');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fee_transactions', function (Blueprint $table) {
            $table->string('mode')->nullable()->after('transaction_mode_id');
        });
        foreach (FeeTransaction::all() as $tx) {
            $mode = FeeTransactionMode::find($tx->transaction_mode_id);
            $tx->mode = $mode->name;
            $tx->save();
        }
        Schema::table('fee_transactions', function (Blueprint $table) {
            $table->dropForeign(['transaction_mode_id']);
            $table->dropColumn('transaction_mode_id');
        });
        Schema::dropIfExists('fee_transaction_modes');
    }
};
