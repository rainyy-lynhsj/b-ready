<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certification extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'workshop_id',
        'assessment_attempt_id',
        'certificate_number',
        'badge_name',
        'certificate_file',
        'certified_at',
    ];

    protected function casts(): array
    {
        return [
            'certified_at' => 'datetime',
        ];
    }

    public function teacher()
    {
        return $this->belongsTo(
            User::class,
            'teacher_id'
        );
    }

    public function workshop()
    {
        return $this->belongsTo(
            Workshop::class,
            'workshop_id'
        );
    }

    public function assessmentAttempt()
    {
        return $this->belongsTo(
            AssessmentAttempt::class,
            'assessment_attempt_id'
        );
    }
}
