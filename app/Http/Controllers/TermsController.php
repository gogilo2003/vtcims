<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;

use App\Models\Term;

use Validator;

class TermsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getTerms()
    {
        $terms = Term::orderBy('created_at', 'DESC')->get();
        return view('eschool::terms.index', compact('terms'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postAdd(Request $request)
    {
        // dd($request->all());

        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                Rule::unique('terms', 'name')->where(function ($query) use ($request) {
                    return $query->where('year', $request->year);
                })
            ],
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'year' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors($validator)
                ->with('global-warning', 'Some fields failed validation. Please check and try again');
        }

        $term = new Term;

        $term->year = $request->year;
        $term->name = $request->name;
        $term->start_date = $request->start_date;
        $term->end_date = $request->end_date;

        $term->save();

        return redirect()
            ->back()
            ->with('global-success', 'Term added');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function postEdit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                Rule::unique('terms', 'name')->ignore($request->id)->where(function ($query) use ($request) {
                    return $query->where('year', $request->year);
                })
            ],
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'year' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors($validator)
                ->with('global-warning', 'Some fields failed validation. Please check and try again');
        }

        $term = Term::find($request->id);

        $term->year = $request->year;
        $term->name = $request->name;
        $term->start_date = $request->start_date;
        $term->end_date = $request->end_date;

        $term->save();

        return redirect()
            ->back()
            ->with('global-success', 'Term updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
