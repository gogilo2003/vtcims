<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Staff extends Model
{
    use HasFactory;

    public function department()
    {
        return $this->hasOne('App\Models\Department');
    }

    public function course()
    {
        return $this->hasOne('App\Models\Course');
    }

    public function intake()
    {
        return $this->hasOne('App\Models\Intake');
    }

    public function subject()
    {
        return $this->belongsToMany('App\Models\Subject');
    }

    public function role()
    {
        return $this->belongsTo('App\Models\StaffRole', 'staff_role_id');
    }

    public function getNameAttribute()
    {
        return $this->surname . ' ' . $this->first_name . ' ' . $this->middle_name;
    }

    public function getMinNameAttribute()
    {
        return $this->first_name . ' ' . ($this->middle_name ? $this->middle_name : $this->surname);
    }

    public function subjects()
    {
        return $this->belongsToMany('App\Models\Subject')->withTimestamps();
    }

    public function getSubjectIdsAttribute()
    {
        return $this->subjects->pluck('id');
    }

    public function intake_subjects()
    {
        return $this->hasMany('App\Models\IntakeStaffSubject', 'subject_id');
    }

    public function getAddressAttribute()
    {
        return 'P.O. Box ' . $this->box_no . ($this->post_code ? ' - ' . $this->post_code . ', ' : ', ') . $this->town;
    }

    public function leaveouts()
    {
        return $this->hasMany('App\Models\LeaveOut');
    }

    public function admin()
    {
        return $this->belongsTo('App\Models\Admin');
    }
    /**
     * Get the status that owns the Staff
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(StaffStatus::class, 'status_id', 'id');
    }

    /**
     * Get all of the allocations for the Staff
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function allocations(): HasMany
    {
        return $this->hasMany(Allocation::class);
    }
}
