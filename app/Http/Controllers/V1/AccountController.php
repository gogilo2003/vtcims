<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AccountController extends Controller
{
    /**
     * Get Accounts Dashboard
     * @return \Inertia\Response
     */
    function index()
    {
        return Inertia::render('Accounts/Index');
    }
}
