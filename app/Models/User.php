<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'role_id',
        'name',
        'email',
        'password',
        'phone',
        'avatar',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function student()
    {
        return $this->hasOne(Student::class);
    }

    public function counselor()
    {
        return $this->hasOne(Counselor::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function auditLogs()
    {
        return $this->hasMany(AuditLog::class);
    }

    // Role helper methods (support both siswa/guru_bk & mahasiswa/konselor)
    public function isStudent(): bool
    {
        return $this->role && in_array($this->role->name, ['siswa', 'mahasiswa']);
    }

    public function isCounselor(): bool
    {
        return $this->role && in_array($this->role->name, ['guru_bk', 'konselor']);
    }

    public function isAdmin(): bool
    {
        return $this->role && in_array($this->role->name, ['administrator', 'admin']);
    }
}
