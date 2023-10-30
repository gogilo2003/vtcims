<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    public function staff()
    {
        return $this->belongsTo('App\Models\Staff');
    }

    public function department()
    {
        return $this->belongsTo('App\Models\Department');
    }

    public function intakes()
    {
        return $this->hasMany('App\Models\Intake');
    }

    public function fees()
    {
        return $this->hasMany('App\Models\Fee');
    }

    public function subjects()
    {
        return $this->belongsToMany('App\Models\Subject')->withTimestamps();
    }

    public function getSubjectIdsAttribute()
    {
        return $this->subjects->pluck('id');
    }
}
