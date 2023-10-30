<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveOut extends Model
{
    public function student()
    {
        return $this->belongsTo('App\Models\Student', 'student_id');
    }

    public function staff()
    {
        return $this->belongsTo('App\Models\Staff', 'staff_id');
    }
}
