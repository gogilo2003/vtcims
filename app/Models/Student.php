<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Student extends Model
{
    use HasFactory;

    protected $appends = [
        'admission_no',
        'name',
        'intake_name',
        'course_name',
        'sponsor_name',
        'program_name',
        'student_role_name',
        'photo_url'
    ];

    public function getPhotoUrlAttribute()
    {
        return $this->photo ? Storage::disk('public')->url($this->photo) : \Laravolt\Avatar\Facade::create($this->name)->toBase64();
    }

    public function getIntakeNameAttribute()
    {
        if ($this->intake) {
            return $this->intake->name;
        }
        return '';
    }

    public function getStudentRoleNameAttribute()
    {
        return $this->role ? $this->role->name : '';
    }

    public function getCourseNameAttribute()
    {
        if ($this->intake) {
            return $this->intake->course->name;
        }
        return '';
    }

    public function getSponsorNameAttribute()
    {
        return $this->sponsor ? $this->sponsor->name : '';
    }

    public function getProgramNameAttribute()
    {
        return $this->program ? $this->program->name : '';
    }

    /**
     * Get the intake that owns the Student
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function intake(): BelongsTo
    {
        return $this->belongsTo(Intake::class);
    }

    public function program()
    {
        return $this->belongsTo('App\Models\Program');
    }

    public function sponsor()
    {
        return $this->belongsTo('App\Models\Sponsor');
    }

    public function role()
    {
        return $this->belongsTo('App\Models\StudentRole', 'student_role_id', 'id');
    }

    public function fee()
    {
        return $this->hasMany('App\Models\Fee');
    }

    public function results()
    {
        return $this->hasMany('App\Models\Result');
    }

    public function tests()
    {
        return $this->hasManyThrough('App\Models\Test', 'App\Models\Result');
    }

    public function getNameAttribute()
    {
        return trim($this->surname . ' ' . $this->first_name . ' ' . $this->middle_name);
    }

    public function getAdmissionNoAttribute()
    {
        if ($this->intake) {
            return strtoupper($this->intake->course->code . '/' . str_pad($this->id, 4, '0', 0) . '/' . date_format(date_create($this->date_of_admission), 'Y'));
        }
        return '';
    }

    public function getAddressAttribute()
    {
        return 'P.O. Box ' . $this->box_no . ($this->post_code ? ' - ' . $this->post_code . ', ' : ', ') . $this->town;
    }

    public function leaveouts()
    {
        return $this->hasMany('App\Models\LeaveOut');
    }
}
