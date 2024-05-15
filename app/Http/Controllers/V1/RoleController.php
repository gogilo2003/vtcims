<?php

namespace App\Http\Controllers\V1;

use App\Models\Role;
use App\Support\Util;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreRoleRequest;
use App\Http\Requests\V1\UpdateRoleRequest;
use Inertia\Inertia;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $permissions = Util::getRoutes();
        $roles = Role::paginate(8)->through(fn(Role $role) => [
            "id" => $role->id,
            "name" => $role->name,
            "permissions" => collect(explode(',', $role->permissions))->map(fn($name) => [
                "name" => $name,
                "caption" => ucwords(str_replace('-', ' ', $name))
            ]),
        ]);
        return Inertia::render('Administrator/Roles/Index', ['roles' => $roles, 'permissions' => $permissions]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoleRequest $request)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRoleRequest $request, Role $role)
    {

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        //
    }
}
