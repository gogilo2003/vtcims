<?php

namespace App\Http\Controllers\V1;

use App\Models\Employer;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreEmployerRequest;
use App\Http\Requests\V1\UpdateEmployerRequest;
use Inertia\Inertia;

class EmployerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $search = request()->input("search");
        $employers = Employer::when($search, function ($query) use ($search) {
            $query->where("name", "like", "%" . $search . "%");
        })->paginate(8)->through(fn(Employer $employer) => [
                "id" => $employer->id,
                "name" => $employer->name
            ]);

        return Inertia::render("Staff/StaffEmployers", ['employers' => $employers, 'search' => $search]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEmployerRequest $request)
    {
        $employer = new Employer();
        $employer->name = $request->input('name');
        $employer->save();
        return redirect()->back()->with('success', 'Employer Created');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEmployerRequest $request, Employer $employer)
    {
        $employer->name = $request->input('name');
        $employer->save();
        return redirect()->back()->with('success', 'Employer updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employer $employer)
    {
        $employer->delete();
        return redirect()->back()->with('success', 'Employer Deleted');
    }
}
