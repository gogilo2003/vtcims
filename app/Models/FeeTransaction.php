<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeeTransaction extends Model
{
    public function student()
    {
        return $this->belongsTo('App\Models\Student');
    }

    public function fee()
    {
        return $this->belongsTo('App\Models\Fee');
    }

    public function vote_head()
    {
        return $this->belongsTo('App\Models\FeeVoteHead');
    }
}
