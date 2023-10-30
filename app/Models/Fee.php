<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fee extends Model
{
    public function course()
    {
        return $this->belongsTo('App\Models\Course');
    }

    public function term()
    {
        return $this->belongsTo('App\Models\Term');
    }

    public function vote_heads()
    {
        return $this->hasMany('App\Models\FeeVoteHeads');
    }

    public function transactions()
    {
        return $this->hasMany('App\Models\FeeTransaction');
    }
}
