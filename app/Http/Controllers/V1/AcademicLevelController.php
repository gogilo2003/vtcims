<?php
namespace App\Http\Controllers\V1;



use Inertia\Inertia;
use App\Models\AcademicLevel;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreAcademicLevelRequest;
use App\Http\Requests\V1\UpdateAcademicLevelRequest;

class AcademicLevelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $search = request()->input('search');

        $academicLevels = AcademicLevel::when($search, function ($query) use ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        });

        return Inertia::render('Students/AcademicLevels', ['search' => $search, 'academic_levels' => $academicLevels]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAcademicLevelRequest $request)
    {
        $academicLevel = new AcademicLevel();
        $academicLevel->name = $request->name;
        $academicLevel->save();

        return redirect()->back()->with('success', 'Academic Level stored');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAcademicLevelRequest $request, AcademicLevel $academicLevel)
    {
        $academicLevel->name = $request->name;
        $academicLevel->save();

        return redirect()->back()->with('success', 'Academic Level updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AcademicLevel $academicLevel)
    {
        $academicLevel->delete();

        return redirect()->back()->with('success', 'Academic Level deleted');
    }
}
