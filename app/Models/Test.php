<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    protected $table = "examination_tests";

    public function examination()
    {
        return $this->belongsTo('App\Models\Examination');
    }

    public function results()
    {
        return $this->hasMany('App\Models\Result');
    }
}
