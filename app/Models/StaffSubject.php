<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StaffSubject extends Model
{
    protected $table = 'staff_subject';
    use HasFactory;

    /**
     * Get the staff that owns the StaffSubject
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class);
    }

    /**
     * Get the subject that owns the StaffSubject
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Get all of the intakes for the StaffSubject
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function intakes(): HasMany
    {
        return $this->hasMany(Allocation::class);
    }
}
