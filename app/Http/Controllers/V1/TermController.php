<?php

namespace App\Http\Controllers\V1;

use App\Models\Term;
use Inertia\Inertia;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTermRequest;
use App\Http\Requests\UpdateTermRequest;

class TermController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $search = request()->input('search');
        $terms = Term::orderBy('year', 'DESC')->orderBy('name', 'DESC')->when($search, function ($query) use ($search) {
            $query->where('name', 'like', '%' . $search . '%')
                ->orWhere('year', 'like', '%' . $search . '%');
        })
            ->paginate(8)->through(fn(Term $term) => [
                "id" => $term->id,
                "year" => $term->year,
                "name" => $term->name,
                "start_date" => Carbon::parse($term->start_date)->isoFormat('ddd, D MMM Y'),
                "end_date" => $term->end_date->isoFormat('ddd, D MMM Y'),
            ]);

        return Inertia::render('Terms/Index', ['terms' => $terms]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\StoreTermRequest $request
     * @return void
     */
    public function store(StoreTermRequest $request)
    {
        $term = new Term();
        $term->name = $request->name;
        $term->year = $request->year;
        $term->start_at = $request->start_at;
        $term->end_at = $request->end_at;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTermRequest $request, Term $term)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Term $term)
    {
        //
    }
}
