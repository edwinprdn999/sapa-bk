<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FollowUp extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'student_id',
        'counselor_id',
        'action_plan',
        'due_date',
        'status',
        'student_notes',
    ];

    protected $casts = [
        'due_date' => 'date',
    ];

    public function session()
    {
        return $this->belongsTo(CounselingSession::class, 'session_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function counselor()
    {
        return $this->belongsTo(Counselor::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Belum Dimulai',
            'in_progress' => 'Sedang Berjalan',
            'completed' => 'Tuntas / Selesai',
            default => ucfirst($this->status),
        };
    }
}
