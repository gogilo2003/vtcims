<?php

namespace App\Models;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;

// Represents a class of students and is named according to course/year/intake series
class Intake extends Model
{
    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function staff_subjects()
    {
        return $this->hasMany(IntakeStaffSubject::class, 'intake_id', 'id');
    }

    public function examinations()
    {
        return $this->belongsToMany(Examination::class)->withTimestamps();
    }

    /**
     * Get all of the allocations for the Intake
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function allocations(): HasMany
    {
        return $this->hasMany(Allocation::class);
    }

    public function startDate(): Attribute
    {
        return new Attribute(
            get: fn($value) => Carbon::parse($value),
            set: fn($value) => Carbon::parse($value),
        );
    }
    public function endDate(): Attribute
    {
        return new Attribute(
            get: fn($value) => Carbon::parse($value),
            set: fn($value) => Carbon::parse($value),
        );
    }
}
