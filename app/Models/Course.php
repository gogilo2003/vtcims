<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function intakes()
    {
        return $this->hasMany(Intake::class);
    }

    public function fees()
    {
        return $this->hasMany(Fee::class);
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class)->withTimestamps();
    }

    public function getSubjectIdsAttribute()
    {
        return $this->subjects->pluck('id');
    }
}
