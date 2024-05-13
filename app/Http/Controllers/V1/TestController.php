<?php

namespace App\Http\Controllers\V1;

use App\Models\Test;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreTestRequest;
use App\Http\Requests\V1\UpdateTestRequest;

class TestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTestRequest $request)
    {
        $test = new Test();
        $test->examination_id = $request->examination;
        $test->outof = $request->outof;
        $test->title = $request->title;
        $test->taken_on = Carbon::parse($request->taken_on);
        $test->save();

        return redirect()->back()->with('success', 'Test Created');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTestRequest $request, Test $test)
    {
        $test->examination_id = $request->examination;
        $test->outof = $request->outof;
        $test->title = $request->title;
        $test->taken_on = Carbon::parse($request->taken_on);
        $test->save();

        return redirect()->back()->with('success', 'Test updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Test $test)
    {
        try {
            Test::destroy($test->id);
            return redirect()->back()->with('success', 'Test deleted');
        } catch (\Throwable $th) {
            throw $th;
        }

    }
}
