<?php

namespace App\Http\Controllers\V1;

use App\Models\Term;
use Inertia\Inertia;
use App\Models\Allocation;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\V1\StoreTermRequest;
use App\Http\Requests\V1\UpdateTermRequest;

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
     * Store newly created term to the database
     *
     * @param \App\Http\Requests\V1\StoreTermRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreTermRequest $request): RedirectResponse
    {
        dd("Here");
        // $term = new Term();
        // $term->name = $request->name;
        // $term->year = $request->year;
        // $term->start_at = $request->start_at;
        // $term->end_at = $request->end_at;
        // $term->save();

        // if ($request->has('auto_allocate')) {
        //     $lastTerm = Term::with('allocations')->whereNot('id', $term->id)->orderBy('id', 'DESC')->first();
        //     foreach ($lastTerm->allocations as $allocation) {
        //         $newAllocation = new Allocation();
        //         $newAllocation->staff_id = $allocation->staff_id;
        //         $newAllocation->subject_id = $allocation->subject_id;
        //         $newAllocation->term_id = $allocation->term_id;
        //         $newAllocation->save();

        //         $newAllocation->intakes()->sync($allocation->intakes->pluck('id')->toArray());
        //     }
        // }

        return redirect()->back()->with('success', 'Term Created');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTermRequest $request, Term $term): RedirectResponse
    {
        $term->name = $request->name;
        $term->year = $request->year;
        $term->start_at = $request->start_at;
        $term->end_at = $request->end_at;
        $term->save();

        return redirect()->back()->with('success', 'Term updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Term $term)
    {
        //
    }
}
