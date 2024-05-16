<?php

namespace App\Http\Controllers\V1;

use App\Models\Role;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Staff;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreUserRequest;
use App\Http\Requests\V1\UpdateUserRequest;

class UserController extends Controller
{
    function index()
    {
        $search = request()->input('search');

        $users = User::orderBy('name', 'ASC')->when($search, function ($query) use ($search) {
            $query->where('name', 'like', '%' . $search . '%')
                ->orWhere('email', 'like', '%' . $search . '%');
        })->paginate(8)->through(fn(User $user) => [
                "id" => $user->id,
                "name" => $user->name,
                "email" => $user->email,
                "staff" => $user->staff ? $user->staff->id : null,
                "roles" => $user->roles->pluck('id')->toArray(),
                "is_admin" => $user->is_admin,
            ]);

        $staff = Staff::orderBy('first_name', 'ASC')->whereHas('status', function ($query) {
            $query->where('name', 'like', '%current%');
        })->get()
            ->map(fn(Staff $staff) => [
                "id" => $staff->id,
                "name" => Str::title(Str::lower(sprintf("%s %s", $staff->first_name, $staff->surname))),
            ]);

        $roles = Role::all()->map(fn(Role $role) => [
            "id" => $role->id,
            "name" => Str::title(Str::lower($role->name)),
        ]);

        return Inertia::render('Administrator/Users/Index', [
            'users' => $users,
            'staff_members' => $staff,
            'roles' => $roles,
            'search' => $search,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $password = $request->password ?? Str::random(6);
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($password);
        $user->save();

        $user->roles()->sync($request->roles);

        if ($request->staff) {
            $staff = Staff::find($request->staff);
            $staff->user_id = $user->id;
            $staff->save();
        }

        return redirect()->back()->with('success', sprintf('User created, Password: %s', $password));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        $user->roles()->sync($request->roles);

        if ($request->staff) {
            $staff = Staff::find($request->staff);
            $staff->user_id = $user->id;
            $staff->save();
        }

        return redirect()->back()->with('success', 'User updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if ($user->delete()) {
            return redirect()->back()->with('success', 'User deleted');
        }
        return redirect()->back()->with('danger', 'An error occurred while attempting to delete user. Please try again');
    }
}
