<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CounselingNote extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'author_id',
        'confidential_note',
        'evaluation_summary',
        'is_encrypted',
    ];

    public function session()
    {
        return $this->belongsTo(CounselingSession::class, 'session_id');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
