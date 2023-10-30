<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\FeeTransaction;

class FeeTransactionController extends Controller
{
    public function index()
    {
        $transactions = FeeTransaction::all();
        return response()->json($transactions);
    }
}
