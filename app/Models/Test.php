<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    protected $table = "examination_tests";

    public function examination()
    {
        return $this->belongsTo(Examination::class);
    }

    public function results()
    {
        return $this->hasMany(Result::class);
    }
}
