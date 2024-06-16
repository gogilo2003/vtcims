<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TradeArea extends Model
{
    use HasFactory;

    /**
     * Get all of the staff for the TradeArea
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function staff(): HasMany
    {
        return $this->hasMany(Staff::class);
    }
}
