<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Ogilo\ApiResponseHelpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class StaffController extends Controller
{
    use ApiResponseHelpers;

    public function index()
    {
    }
    public function status(Request $request)
    {
        return $this->respondWithSuccess("Status");
    }
}
