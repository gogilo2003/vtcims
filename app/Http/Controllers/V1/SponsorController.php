<?php

namespace App\Http\Controllers\V1;

use Inertia\Inertia;
use App\Models\Sponsor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
        })->paginate(10)->through(fn ($item) => [
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
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Sponsor $sponsor)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sponsor $sponsor)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Sponsor $sponsor)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sponsor $sponsor)
    {
        //
    }
}
