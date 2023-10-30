<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IntakeStaffSubject extends Model
{
    protected $table = 'intake_staff_subject';

    public function staff()
    {
        return $this->belongsTo('App\Models\Staff', 'staff_id');
    }

    public function subject()
    {
        return $this->belongsTo('App\Models\Subject', 'subject_id');
    }

    public function intake()
    {
        return $this->belongsTo('App\Models\Intake', 'intake_id');
    }

    public function examinations()
    {
        return $this->hasMany('App\Models\Examination');
    }

    public function getNameAttribute()
    {
        return $this->intake->name . strtoupper(' (' . $this->staff->min_name . ' - ' . $this->subject->name . ')');
    }

    public function getSubjectNameAttribute()
    {
        return $this->intake->name . strtoupper(' (' . $this->subject->name . ')');
    }
}
