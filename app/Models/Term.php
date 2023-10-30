<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Term extends Model
{
    protected $appends = ['year_name'];
    public function fees()
    {
        return $this->hasMany('App\Models\Fee');
    }

    public function examination_groups()
    {
        return $this->hasMany('App\Models\Examination');
    }

    public function intake_staff_subject()
    {
        return $this->belongsTo('App\Models\IntakeStaffSubject');
    }

    public function getYearNameAttribute()
    {
        return strtoupper(date_create($this->start_date)->format('Y') . ' - ' . $this->name);
    }
}
