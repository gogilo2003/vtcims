<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Grade extends Model
{
    /**
     * Get the remark that owns the Grade
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function remark(): BelongsTo
    {
        return $this->belongsTo(Remark::class);
    }
}
