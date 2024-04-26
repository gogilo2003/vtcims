<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Term extends Model
{
    protected $casts = ['start_date' => 'date', 'end_date' => 'date'];

    public function fees()
    {
        return $this->hasMany(Fee::class);
    }

    public function examinations()
    {
        return $this->hasMany(Examination::class);
    }

    public function intake_staff_subject()
    {
        return $this->belongsTo(IntakeStaffSubject::class);
    }

    /**
     * Get all of the allocations for the Term
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function allocations(): HasMany
    {
        return $this->hasMany(Allocation::class);
    }
}
