<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssessmentAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'assessment_id',
        'user_id',
        'score',
        'status',
    ];

    // Relasyon papunta sa Assessment
    public function assessment()
    {
        return $this->belongsTo(Assessment::class);
    }

    // Relasyon papunta sa User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}