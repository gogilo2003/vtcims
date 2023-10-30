<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeeVoteHead extends Model
{
    public function fee()
    {
        return $this->belongsTo('App\Models\Fee');
    }

    public function transactions()
    {
        return $this->hasMany('App\Models\FeeTransaction');
    }
}
