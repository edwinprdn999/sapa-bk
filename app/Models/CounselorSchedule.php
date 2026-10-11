<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CounselorSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'counselor_id',
        'date',
        'start_time',
        'end_time',
        'is_booked',
        'is_active',
    ];

    protected $casts = [
        'date' => 'date',
        'is_booked' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function counselor()
    {
        return $this->belongsTo(Counselor::class);
    }

    public function appointment()
    {
        return $this->hasOne(Appointment::class, 'schedule_id');
    }
}
