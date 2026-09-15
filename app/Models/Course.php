<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'trainer_id',
        'title',
        'description',
        'learning_objectives',
        'target_participants',
        'estimated_duration',
        'status',
    ];

    /**
     * The DRR Trainer who created this course.
     */
    public function trainer()
    {
        return $this->belongsTo(User::class, 'trainer_id');
    }

    /**
     * Modules belonging to this course.
     */
    public function modules()
    {
        return $this->hasMany(Module::class, 'course_id')
            ->orderBy('sequence');
    }
}
