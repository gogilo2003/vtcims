<?php

namespace App\Http\Controllers\V1;

use Inertia\Inertia;
use App\Models\Sponsor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreSponsorRequest;
use App\Http\Requests\V1\UpdateSponsorRequest;

class SponsorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $search = request()->input('search');

        $sponsors = Sponsor::when($search, function ($query) use ($search) {
            $query->where('name', 'LIKE', '%' . $search . '%');
        })->paginate(10)->through(fn($item) => [
                "id" => $item->id,
                "name" => $item->name,
                "contact_person" => $item->contact_person,
                "email" => $item->email,
                "phone" => $item->phone,
                "box_no" => $item->box_no,
                "post_code" => $item->post_code,
                "town" => $item->town,
                "address" => $item->address,
            ]);

        return Inertia::render('Sponsors/Index', ['sponsors' => $sponsors, 'search' => $search,]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSponsorRequest $request)
    {
        $sponsor = new Sponsor;

        $sponsor->name = $request->name;
        $sponsor->contact_person = $request->contact_person;
        $sponsor->email = $request->email;
        $sponsor->phone = $request->phone;
        $sponsor->box_no = $request->box_number;
        $sponsor->post_code = $request->post_code;
        $sponsor->town = $request->town;
        $sponsor->address = $request->physical_address;

        $sponsor->save();

        return redirect()
            ->back()
            ->with('success', 'Sponsor added');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSponsorRequest $request, Sponsor $sponsor)
    {

        $sponsor = Sponsor::find($request->id);

        $sponsor->name = $request->name;
        $sponsor->contact_person = $request->contact_person;
        $sponsor->email = $request->email;
        $sponsor->phone = $request->phone;
        $sponsor->box_no = $request->box_number;
        $sponsor->post_code = $request->post_code;
        $sponsor->town = $request->town;
        $sponsor->address = $request->physical_address;

        $sponsor->save();

        return redirect()
            ->back()
            ->with('success', 'Sponsor updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sponsor $sponsor)
    {
        $sponsor->delete();

        return redirect()
            ->back()
            ->with('success', 'Sponsor deleted');
    }
}
