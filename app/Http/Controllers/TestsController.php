<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;

use App\Models\Examination;
use App\Models\Test;

use Validator;
use App;

class TestsController extends Controller
{
    public function getTests($id = null)
    {

        if ($id) {
            $examination = Examination::with('tests')->findOrFail($id);
            return view('eschool::examinations.tests.index', compact('examination'));
        } else {
            $tests = Test::all();
            return view('eschool::examinations.tests.index', compact('tests'));
        }
    }

    public function postAdd(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'title' => ['required', Rule::unique('examination_tests', 'title')->where(function ($query) use ($request) {
                return $query->where('examination_id', $request->examination);
            })],
            'taken_on' => 'required|date',
            'outof' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors($validator)
                ->with('global-warning', 'Some fields failed validation. Please check and try again');
        }

        $test = new Test();

        $test->title            = $request->title;
        $test->taken_on         = $request->taken_on;
        $test->outof            = $request->outof;
        $test->examination_id   = $request->examination;
        $test->notes            = $request->notes;

        $test->save();

        return redirect()
            ->back()
            ->with('global-success', 'Test added');
    }

    public function postEdit(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'title' => ['required', Rule::unique('examination_tests', 'title')->where(function ($query) use ($request) {
                return $query->where('examination_id', $request->examination);
            })->ignore($request->id, 'id')],
            'taken_on' => 'required|date',
            'outof' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors($validator)
                ->with('global-warning', 'Some fields failed validation. Please check and try again');
        }

        $test = Test::find($request->id);

        $test->title            = $request->title;
        $test->taken_on         = $request->taken_on;
        $test->outof            = $request->outof;
        $test->examination_id   = $request->examination;
        $test->notes            = $request->notes;

        $test->save();

        return redirect()
            ->back()
            ->with('global-success', 'Test updated');
    }
}
