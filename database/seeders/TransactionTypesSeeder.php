<?php

namespace Database\Seeders;

use App\Models\FeeTransactionType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TransactionTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $transactionTypes = [
            (object) ['code' => 'FC', 'description' => 'Fee Charge', 'income' => null],
            (object) ['code' => 'FP', 'description' => 'Fee Payment', 'income' => 1],
            (object) ['code' => 'FR', 'description' => 'Fee Reversal', 'income' => 0],
            (object) ['code' => 'DN', 'description' => 'Donations', 'income' => 1],
            (object) ['code' => 'IGA', 'description' => 'Income Generating Activity', 'income' => 1],
            (object) ['code' => 'PC', 'description' => 'Petty Cash', 'income' => 0],
            (object) ['code' => 'EX', 'description' => 'Expense', 'income' => 0],
        ];

        foreach ($transactionTypes as $value) {
            $type = FeeTransactionType::where('code', $value->code)->first() ?? new FeeTransactionType();
            $type->code = $value->code;
            $type->description = $value->description;
            $type->income = $value->income;
            $type->save();
        }

        // DB::table('fee_transaction_types')->insert($transactionTypes);
    }
}
