<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Student extends Model
{
    use HasFactory;

    protected $casts = [
        'date_of_birth' => 'date',
        'date_of_admission' => 'date',
    ];

    /**
     * Get the intake that owns the Student
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function intake(): BelongsTo
    {
        return $this->belongsTo(Intake::class);
    }

    /**
     * Get the program that owns the Student
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class, 'program_id', 'id');
    }

    /**
     * Get the sponsor that owns the Student
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sponsor(): BelongsTo
    {
        return $this->belongsTo(Sponsor::class, 'sponsor_id', 'id');
    }

    /**
     * Get the role that owns the Student
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(StudentRole::class, 'student_role_id', 'id');
    }

    /**
     * Get all of the fees for the Student
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function fees(): HasMany
    {
        return $this->hasMany(Fee::class, 'student_id', 'id');
    }

    /**
     * Get all of the results for the Student
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function results(): HasMany
    {
        return $this->hasMany(Result::class, 'student_id', 'id');
    }

    /**
     * Get all of the leave_outs for the Student
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function leave_outs(): HasMany
    {
        return $this->hasMany(LeaveOut::class);
    }

    /**
     * The attendances that belong to the Student
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function attendances(): BelongsToMany
    {
        return $this->belongsToMany(Attendance::class);
    }

    /**
     * Get the guardian that owns the Student
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function guardian(): BelongsTo
    {
        return $this->belongsTo(Guardian::class);
    }
}
