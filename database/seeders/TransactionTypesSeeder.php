<?php

namespace Database\Seeders;

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
            ['code' => 'FC', 'description' => 'Fee Charge'],
            ['code' => 'FP', 'description' => 'Fee Payment'],
            ['code' => 'FR', 'description' => 'Fee Reversal'],
        ];

        DB::table('fee_transaction_types')->insert($transactionTypes);
    }
}
