<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'assessment_id',
        'question_text',
        'question_type',
        'points',
        'sequence',
    ];

    public function assessment()
    {
        return $this->belongsTo(
            Assessment::class,
            'assessment_id',
        );
    }

    public function choices()
    {
        return $this->hasMany(
            Choice::class,
            'question_id',
        )->orderBy('sequence');
    }

    public function answers()
    {
        return $this->hasMany(
            AssessmentAnswer::class,
            'question_id'
        );
    }
}
