<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subject extends Model
{
    public function courses()
    {
        return $this->belongsToMany(Course::class)->withTimestamps();
    }

    public function staff()
    {
        return $this->belongsToMany(Staff::class)->withTimestamps();
    }

    public function getCourseIdsAttribute()
    {
        return $this->courses->pluck('id');
    }

    public function getStaffIdsAttribute()
    {
        return $this->staff->pluck('id');
    }

    public function intake_staff()
    {
        return $this->hasMany(IntakeStaffSubject::class);
    }

    public function examinations()
    {
        return $this->hasMany(Examination::class);
    }

    /**
     * Get all of the allocations for the Subject
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function allocations(): HasMany
    {
        return $this->hasMany(Allocation::class, );
    }

    /**
     * Get the subject's  name.
     */
    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn(string $value) => Str::title(Str::lower($value)),
        );
    }
}
