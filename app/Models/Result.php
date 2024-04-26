<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    protected $table = "examination_results";

    public function test()
    {
        return $this->belongsTo(Test::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
