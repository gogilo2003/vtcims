<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// Represents a class of students and is named according to course/year/intake series
class Intake extends Model
{
    public function staff()
    {
        return $this->belongsTo('App\Models\Staff');
    }

    public function course()
    {
        return $this->belongsTo('App\Models\Course');
    }

    public function students()
    {
        return $this->hasMany('App\Models\Student');
    }

    public function staff_subjects()
    {
        return $this->hasMany('App\Models\IntakeStaffSubject', 'intake_id', 'id');
    }

    public function examinations()
    {
        return $this->belongsToMany('App\Models\Examination')->withTimestamps();
    }
}
