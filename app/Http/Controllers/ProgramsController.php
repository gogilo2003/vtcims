<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Program;

class ProgramsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getPrograms()
    {
        $programs = Program::all();
        return view('eschool::programs.index', compact('programs'));
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
        $request->validate(['program_name' => 'required|unique:programs,name']);

        $program = new Program;

        $program->name = $request->program_name;

        $program->save();

        return redirect()
            ->back()
            ->with('global-success', 'Program added');
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
        $request->validate([
            'id' => 'required|exists:programs',
            'program_name' => 'required|unique:programs,name,' . $request->id
        ]);

        $program = Program::find($request->id);

        $program->name = $request->program_name;

        $program->save();

        return redirect()
            ->back()
            ->with('global-success', 'Program updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function postDelete(Request $request)
    {
        $request->validate(['id' => 'required|exists:programs']);

        $program = Program::find($request->id);
        $program->delete();

        return redirect()
            ->back()
            ->with('global-success', 'Program deleted');
    }
}
