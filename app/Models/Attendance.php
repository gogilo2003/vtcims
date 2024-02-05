<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Attendance extends Model
{
    use HasFactory;
    /**
     * Get the allocation that owns the Attendance
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function allocation(): BelongsTo
    {
        return $this->belongsTo(Allocation::class, 'allocation_id', 'id');
    }

    /**
     * The lessons that belong to the Attendance
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function lessons(): BelongsToMany
    {
        return $this->belongsToMany(Lesson::class);
    }

    /**
     * The students that belong to the Attendance
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class);
    }
}
