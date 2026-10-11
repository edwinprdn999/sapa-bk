<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'appointment_code',
        'student_id',
        'counselor_id',
        'service_id',
        'schedule_id',
        'consultation_date',
        'start_time',
        'end_time',
        'method',
        'initial_concern',
        'status',
        'counselor_response_reason',
        'meeting_link_or_room',
    ];

    protected $casts = [
        'consultation_date' => 'date',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function counselor()
    {
        return $this->belongsTo(Counselor::class);
    }

    public function service()
    {
        return $this->belongsTo(CounselingService::class, 'service_id');
    }

    public function schedule()
    {
        return $this->belongsTo(CounselorSchedule::class, 'schedule_id');
    }

    public function session()
    {
        return $this->hasOne(CounselingSession::class);
    }

    // Helper for Status Badges
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Menunggu Konfirmasi',
            'approved' => 'Disetujui',
            'reschedule_requested' => 'Perlu Jadwal Ulang',
            'rejected' => 'Ditolak',
            'cancelled' => 'Dibatalkan',
            'in_progress' => 'Sedang Berlangsung',
            'completed' => 'Selesai',
            'no_show' => 'Tidak Hadir',
            default => ucfirst($this->status),
        };
    }

    public function getStatusColorClassAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'bg-amber-100 text-amber-800 border-amber-200',
            'approved' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
            'reschedule_requested' => 'bg-orange-100 text-orange-800 border-orange-200',
            'rejected' => 'bg-rose-100 text-rose-800 border-rose-200',
            'cancelled' => 'bg-slate-100 text-slate-800 border-slate-200',
            'in_progress' => 'bg-blue-100 text-blue-800 border-blue-200',
            'completed' => 'bg-indigo-100 text-indigo-800 border-indigo-200',
            'no_show' => 'bg-purple-100 text-purple-800 border-purple-200',
            default => 'bg-slate-100 text-slate-800',
        };
    }
}
