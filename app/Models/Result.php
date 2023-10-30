<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    protected $table = "examination_results";

    public function test()
    {
        return $this->belongsTo('App\Models\Test');
    }

    public function student()
    {
        return $this->belongsTo('App\Models\Student');
    }
}
