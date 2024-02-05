<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Lesson extends Model
{
    use HasFactory;

    /**
     * The attendances that belong to the Lesson
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function attendances(): BelongsToMany
    {
        return $this->belongsToMany(Attendance::class);
    }
}
