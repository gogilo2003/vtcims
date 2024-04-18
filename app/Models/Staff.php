<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Staff extends Model
{
    use HasFactory;

    /**
     * Get the department associated with the Staff
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function department(): HasOne
    {
        return $this->hasOne(Department::class);
    }

    /**
     * Get the course associated with the Staff
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function course(): HasOne
    {
        return $this->hasOne(Course::class);
    }

    /**
     * Get the intake associated with the Staff
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function intake(): HasOne
    {
        return $this->hasOne(Intake::class);
    }

    /**
     * The subjects that belong to the Staff
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class)->withTimestamps();
    }

    /**
     * Get the role that owns the Staff
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(StaffRole::class, 'staff_role_id', 'id');
    }

    public function getNameAttribute()
    {
        return $this->surname . ' ' . $this->first_name . ' ' . $this->middle_name;
    }

    public function getMinNameAttribute()
    {
        return $this->first_name . ' ' . ($this->middle_name ? $this->middle_name : $this->surname);
    }

    public function getSubjectIdsAttribute()
    {
        return $this->subjects->pluck('id');
    }

    /**
     * Get all of the intake_subjects for the Staff
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function intake_subjects(): HasMany
    {
        return $this->hasMany(IntakeStaffSubject::class, 'subject_id', 'id');
    }

    public function getAddressAttribute()
    {
        return 'P.O. Box ' . $this->box_no . ($this->post_code ? ' - ' . $this->post_code . ', ' : ', ') . $this->town;
    }

    /**
     * Get all of the leavouts for the Staff
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function leavouts(): HasMany
    {
        return $this->hasMany(LeaveOut::class);
    }

    /**
     * Get the admin that owns the Staff
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class);
    }

    /**
     * Get the user that owns the Staff
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    /**
     * Get the status that owns the Staff
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(StaffStatus::class, 'staff_status_id', 'id');
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
