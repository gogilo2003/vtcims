<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Lesson extends Model
{
    use HasFactory;

    protected $casts = [
        "start_at" => "datetime",
        "end_at" => "datetime",
    ];

    /**
     * The allocations  that belong to the Lesson
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function allocations(): BelongsToMany
    {
        return $this->belongsToMany(Allocation::class);
    }

    /**
     * Get all of the allocation_lessons for the Lesson
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function allocation_lessons(): HasMany
    {
        return $this->hasMany(AllocationLesson::class);
    }
}
