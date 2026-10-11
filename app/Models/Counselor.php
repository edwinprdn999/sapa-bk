<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Counselor extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'employee_number',
        'specialization',
        'room_location',
        'bio',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function schedules()
    {
        return $this->hasMany(CounselorSchedule::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function sessions()
    {
        return $this->hasMany(CounselingSession::class);
    }

    public function followUps()
    {
        return $this->hasMany(FollowUp::class);
    }
}
