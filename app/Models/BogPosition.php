<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BogPosition extends Model
{
    use HasFactory;

    /**
     * Get all of the members for the BogPosition
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function members(): HasMany
    {
        return $this->hasMany(BogMember::class, 'bog_position_id', 'id');
    }
}
