<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Fee extends Model
{
    /**
     * Get the course that owns the Fee
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get the term that owns the Fee
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function term(): BelongsTo
    {
        return $this->belongsTo(Term::class);
    }
    /**
     * Get all of the transactions for the Fee
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(FeeTransaction::class);
    }
    /**
     * Get all of the voteHeads for the Fee
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function voteHeads(): HasMany
    {
        return $this->hasMany(FeeVoteHead::class);
    }
}
