<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    public function hod()
    {
        return $this->belongsTo('App\Models\Staff', 'staff_id');
    }

    public function courses()
    {
        return $this->hasMany('App\Models\Course');
    }

    public function intakes()
    {
        return $this->hasManyThrough('App\Models\Intake', 'App\Models\Course');
    }
}
