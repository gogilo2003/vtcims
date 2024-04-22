<?php

namespace App\Http\Controllers\V1;

use App\Models\JobGroup;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreJobGroupRequest;
use App\Http\Requests\V1\UpdateJobGroupRequest;
use Inertia\Inertia;

class JobGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $search = request()->input('search');

        $job_groups = JobGroup::when($search, function ($query) use ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        })->paginate(8);

        return Inertia::render('Staff/JobGroups', ['job_groups' => $job_groups]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreJobGroupRequest $request)
    {
        $jobGroup = new JobGroup();

        $jobGroup->name = $request->name;
        $jobGroup->save();

        return redirect()->back()->with('success', 'Job Group Created');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateJobGroupRequest $request, JobGroup $jobGroup)
    {
        $jobGroup->name = $request->name;
        $jobGroup->save();

        return redirect()->back()->with('success', 'Job Group Updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JobGroup $jobGroup)
    {
        $jobGroup->delete();

        return redirect()->back()->with('success', 'Job Group deleted');
    }
}
