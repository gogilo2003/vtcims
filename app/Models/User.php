<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    function isAdmin(): bool
    {
        return $this->is_admin;
    }

    function hasPermission(string $permission = null): bool
    {
        if ($this->isAdmin()) {
            return true;
        }

        $permissions = [];
        $strPermissions = "";

        foreach ($this->roles as $role) {
            $strPermissions .= "," . $role->permissions;
        }

        $permissions = collect(explode(",", $strPermissions))->unique();
        // dd($permission, $strPermissions, $permissions, $permissions->contains($permission));

        return $permissions->contains($permission);
    }

    /**
     * The roles that belong to the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * Get the staff associated with the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function staff(): HasOne
    {
        return $this->hasOne(Staff::class);
    }
}
