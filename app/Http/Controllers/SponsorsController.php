<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Sponsor;

class SponsorsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getSponsors()
    {
        $sponsors = Sponsor::all();
        return view('eschool::sponsors.index', compact('sponsors'));
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
        $request->validate([
            'sponsor_name' => 'required|unique:sponsors,name',
            'contact_person' => 'required',
            'email' => 'nullable|email',
        ]);

        $sponsor = new Sponsor;

        $sponsor->name = $request->sponsor_name;
        $sponsor->contact_person = $request->contact_person;
        $sponsor->email = $request->email;
        $sponsor->phone = $request->phone_number ? clean_isdn($request->phone_number) : '';
        $sponsor->box_no = $request->box_number;
        $sponsor->post_code = $request->post_code;
        $sponsor->town = $request->town;
        $sponsor->address = $request->physical_address;

        $sponsor->save();

        return redirect()
            ->back()
            ->with('global-success', 'Sponsor added');
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
            'id' => 'required|exists:sponsors',
            'sponsor_name' => 'required|unique:sponsors,name,' . $request->id,
            'contact_person' => 'required',
            'email' => 'nullable|email',
        ]);

        $sponsor = Sponsor::find($request->id);

        $sponsor->name = $request->sponsor_name;
        $sponsor->contact_person = $request->contact_person;
        $sponsor->email = $request->email;
        $sponsor->phone = $request->phone_number ? clean_isdn($request->phone_number) : '';
        $sponsor->box_no = $request->box_number;
        $sponsor->post_code = $request->post_code;
        $sponsor->town = $request->town;
        $sponsor->address = $request->physical_address;

        $sponsor->save();

        return redirect()
            ->back()
            ->with('global-success', 'Sponsor updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function postDelete(Request $request)
    {
        $request->validate(['id' => 'required|exists:sponsors']);

        $sponsor = Sponsor::find($request->id);
        $sponsor->delete();

        return redirect()
            ->back()
            ->with('global-success', 'Sponsor deleted');
    }
}
