<?php

namespace App\Http\Controllers\V1;

use Inertia\Inertia;
use Illuminate\Support\Str;
use App\Models\FeeTransaction;
use App\Models\FeeTransactionType;
use App\Http\Controllers\Controller;

class AccountController extends Controller
{
    /**
     * Get Accounts Dashboard
     * @return \Inertia\Response
     */
    function index()
    {

        $stats = [];

        foreach (FeeTransactionType::all() as $type) {
            $stats = [
                "title" => Str::title(Str::lower($type->description)),
                "amount" => $type->fee_transactions->sum('amount')
            ];
        }

        return Inertia::render('Accounts/Index', ['stats' => $stats]);
    }
}
