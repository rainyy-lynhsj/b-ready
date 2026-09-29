<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'assessment_id',
        'certificate_code',
        'issued_at',
    ];

    // Relasyon papunta sa User (Estudyante / Trainer)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasyon papunta sa Assessment (Pagsusulit)
    public function assessment()
    {
        return $this->belongsTo(Assessment::class);
    }
}