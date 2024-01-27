<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subject extends Model
{
    public function courses()
    {
        return $this->belongsToMany('App\Models\Course')->withTimestamps();
    }

    public function staff()
    {
        return $this->belongsToMany('App\Models\Staff')->withTimestamps();
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
        return $this->hasMany('App\Models\IntakeStaffSubject');
    }

    public function examinations()
    {
        return $this->hasMany('App\Models\Examination');
    }

    /**
     * Get all of the allocations for the Subject
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function allocations(): HasMany
    {
        return $this->hasMany(Allocation::class,);
    }
}
