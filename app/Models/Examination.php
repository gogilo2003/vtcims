<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Examination extends Model
{
    protected $appends = ['intake_ids'];
    public function term()
    {
        return $this->belongsTo('App\Models\Term');
    }

    public function tests()
    {
        return $this->hasMany('App\Models\Test');
    }

    public function results()
    {
        return $this->hasManyThrough('App\Models\Result', 'App\Models\Test');
    }

    public function subject()
    {
        return $this->belongsTo('App\Models\Subject');
    }

    public function intakes()
    {
        return $this->belongsToMany(Intake::class)->withTimestamps();
    }

    public function getIntakeIdsAttribute()
    {
        return $this->intakes()->pluck('intakes.id')->toArray();
    }
}
