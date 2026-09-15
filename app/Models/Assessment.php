<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assessment extends Model
{
    use HasFactory;

    protected $fillable = [
        'workshop_id',
        'title',
        'description',
        'passing_score',
        'time_limit',
        'attempts_allowed',
    ];

    public function workshop()
    {
        return $this->hasOne(
            Workshop::class,
            'assessment_id'
        );
    }

    public function questions()
    {
        return $this->hasMany(
            Question::class,
            'assessment_id'
        )->orderBy('sequence');
    }

    public function attempts()
    {
        return $this->hasMany(
            AssessmentAttempt::class,
            'assessment_id'
        );
    }
}
