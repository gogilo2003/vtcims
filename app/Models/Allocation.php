<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Allocation extends Model
{
    use HasFactory;

    protected $table = 'staff_subject';

    /**
     * The intakes that belong to the Allocation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function intakes(): BelongsToMany
    {
        return $this->belongsToMany(Intake::class, 'intake_staff_subject', 'staff_subject_id');
    }

    /**
     * Get the staff that owns the Allocation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'staff_id', 'id');
    }

    /**
     * Get the subject that owns the Allocation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Get the term that owns the Allocation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function term(): BelongsTo
    {
        return $this->belongsTo(Term::class);
    }

    /**
     * Get all of the attendances for the Allocation
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function attendances(): HasManyThrough
    {
        return $this->hasManyThrough(Attendance::class, AllocationLesson::class);
    }

    /**
     * The lessons that belong to the Allocation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function lessons(): BelongsToMany
    {
        return $this->belongsToMany(Lesson::class);
    }

    /**
     * Get all of the allocation_lessons for the Allocation
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function allocation_lessons(): HasMany
    {
        return $this->hasMany(AllocationLesson::class);
    }
}
