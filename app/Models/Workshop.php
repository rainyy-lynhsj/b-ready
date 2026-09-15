<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Workshop extends Model
{
    use HasFactory;

    protected $fillable = [
        'trainer_id',
        'title',
        'description',
        'start_date',
        'end_date',
        'registration_deadline',
        'assessment_id',
        'invite_link',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'datetime',
            'end_date' => 'datetime',
            'registration_deadline' => 'datetime',
        ];
    }

    public function trainer()
    {
        return $this->belongsTo(User::class, 'trainer_id');
    }

    public function teachers()
    {
        return $this->belongsToMany(
            User::class, 
            'workshop_teachers',
            'workshop_id',
            'teacher_id'
        )->withPivot('status', 'joined_at');
    }

    public function workshopTeachers()
    {
        return $this->hasMany(
            WorkshopTeachers::class,
            'workshop_id'
        );
    }

    public function workshopModules()
    {
        return $this->hasMany(
            WorkshopModule::class,
            'workshop_id'
        )->orderBy('sequence');
    }

    public function modules()
    {
        return $this->belongsToMany(
            Module::class,
            'workshop_modules',
            'workshop_id',
            'module_id'
        )->withPivot('sequence');
    }

    public function assessment()
    {
        return $this->belongsTo(
            Assessment::class,
            'assessment_id'
        );
    }
}
