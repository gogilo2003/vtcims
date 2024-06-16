<?php

namespace App\Http\Controllers\V1;

use App\Models\TradeArea;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreTradeAreaRequest;
use App\Http\Requests\V1\UpdateTradeAreaRequest;
use App\Models\Staff;
use Inertia\Inertia;

class TradeAreaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $search = request()->input('search');

        $tradeAreas = TradeArea::when($search, function ($query) use ($search) {
            $query->where('name', 'like', '%' . $search . '%')
                ->orWhere('name', 'like', '%' . $search . '%');
        })->paginate(8)->through(fn(TradeArea $tradeArea) => [
                "id" => $tradeArea->id,
                "name" => $tradeArea->name,
                "description" => $tradeArea->description,
                "staff" => $tradeArea->staff->map(fn(Staff $staff) => [
                    "id" => $staff->id,
                    "name" => sprintf($staff->name),
                ]),
            ]);

        return Inertia::render('Staff/TradeAreas', [
            "trade_areas" => $tradeAreas,
            "search" => $search,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTradeAreaRequest $request)
    {
        $tradeArea = new TradeArea();

        $tradeArea->name = $request->name;
        $tradeArea->description = $request->description;
        $tradeArea->save();

        return redirect()->back()->with('success', 'Trade Area created');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTradeAreaRequest $request, TradeArea $tradeArea)
    {
        $tradeArea->name = $request->name;
        $tradeArea->description = $request->description;
        $tradeArea->save();

        return redirect()->back()->with('success', 'Trade Area updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TradeArea $tradeArea)
    {
        $tradeArea->delete();

        return redirect()->back()->with('success', 'Trade Area deleted');
    }
}
