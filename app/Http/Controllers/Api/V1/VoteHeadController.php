<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\FeeVoteHead;
use App\Http\Resources\FeeVoteHeadResource;

class VoteHeadController extends Controller
{
    public function index()
    {
        return FeeVoteHeadResource::collection(FeeVoteHead::all());
    }

    public function store(Request $request)
    {
        # code...
    }

    public function update(Request $request)
    {
    }
}
