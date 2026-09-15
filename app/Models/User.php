<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];
    
    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Courses created by this DRR Trainer.
     */
    public function courses()
    {
        return $this->hasMany(Course::class, 'trainer_id');
    }

    /**
     * Workshops created by this DRR Trainer.
     */
    public function createdWorkshops()
    {
        return $this->hasMany(Workshop::class, 'trainer_id');
    }

    /**
     * Workshops joined by this Teacher.
     */
    public function workshops()
    {
        return $this->belongsToMany(
            Workshop::class,
            'workshop_teachers',
            'teacher_id',
            'workshop_id'
        )->withPivot('status', 'joined_at');
    }

    /**
     * Module progress recods for this Teacher.
     */
    public function moduleProgress()
    {
        return $this->hasMany(ModuleProgress::class, 'teacher_id');
    }

    /**
     * Assessment attempts made by this Teacher.
     */
    public function assessmentAttempts()
    {
        return $this->hasMany(AssessmentAttempt::class, 'teacher_id');
    }
    
    /**
     * Classroom implementations conducted by this Teacher.
     */
    public function classroomImplementations()
    {
        return $this->hasMany(
            ClassroomImplementation::class,
            'teacher_id'
        );
    }
}
