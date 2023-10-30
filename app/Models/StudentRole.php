<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentRole extends Model
{
    public function students()
    {
        return $this->hasMany('App\Models\Student', 'student_role_id', 'id');
    }
}
