<?php

namespace App\Http\Controllers\Vtcims;

use App\Http\Controllers\Controller;
use Inertia\Inertia;

class StudentController extends Controller
{
    function index()
    {
        return Inertia::render('Students/Index');
    }
}
