<?php

namespace App\Http\Middleware;

use App\Support\Util;
use Illuminate\Http\Request;
use Inertia\Middleware;
use Tightenco\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): string|null
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $notification = [];

        if (session('success')) {
            $notification['success'] = session('success');
        }
        if (session('warning')) {
            $notification['warning'] = session('warning');
        }
        if (session('danger')) {
            $notification['danger'] = session('danger');
        }
        if (session('info')) {
            $notification['info'] = session('info');
        }

        $userData = null;
        if ($user = $request->user()) {

            $permissions = $user->is_admin ? Util::getRoutes()->pluck('name') : $user->roles->flatMap(function ($role) {
                return explode(',', $role->permissions);
            })->unique()->values()->all();

            $userData = [
                "id" => $user->id,
                "name" => $user->name,
                "email" => $user->email,
                "is_admin" => $user->is_admin,
                "email_verified_at" => $user->email_verified_at,
                "permissions" => $permissions,
            ];
        }
        return [
            ...parent::share($request),
            'auth' => [
                'user' => $userData,
            ],
            'ziggy' => fn() => [
                ...(new Ziggy)->toArray(),
                'location' => $request->url(),
            ],
            'notification' => $notification,
        ];
    }
}
