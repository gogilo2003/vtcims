<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Fee;

class FeesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getFees()
    {
        return view('eschool::fees.index');
    }

    public function getFeeTransactions()
    {
        return view('eschool::fees.transactions');
    }
    public function getFeeVoteHeads()
    {
        return view('eschool::fees.vote_heads');
    }
}
