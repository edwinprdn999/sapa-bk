<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CounselingSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'appointment_id',
        'counselor_id',
        'student_id',
        'started_at',
        'ended_at',
        'attendance_status',
        'public_summary',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
    ];

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function counselor()
    {
        return $this->belongsTo(Counselor::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function confidentialNote()
    {
        return $this->hasOne(CounselingNote::class, 'session_id');
    }

    public function followUps()
    {
        return $this->hasMany(FollowUp::class, 'session_id');
    }
}
