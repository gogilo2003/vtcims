<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Examination extends Model
{

    /**
     * Get the term that owns the Examination
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function term(): BelongsTo
    {
        return $this->belongsTo(Term::class);
    }

    /**
     * Get all of the tests for the Examination
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tests(): HasMany
    {
        return $this->hasMany(Test::class);
    }

    /**
     * Get all of the results for the examination
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function results(): HasManyThrough
    {
        return $this->hasManyThrough(Result::class, Test::class);
    }

    /**
     * Get the subject that owns the Examination
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * The intakes that belong to the Examination
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function intakes(): BelongsToMany
    {
        return $this->belongsToMany(Intake::class)->withTimestamps();
    }
}
