<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'title',
        'description',
        'learning_objectives',
        'estimated_duration',
        'sequence',
        'is_required',
    ];

    public function course()
    {
        return $this->belongsTo(
            Course::class,
            'course_id'
        );
    }

    public function materials()
    {
        return $this->hasMany(
            Material::class,
            'module_id'
        );
    }

    public function workshopModules()
    {
        return $this->hasMany(
            WorkshopModule::class,
            'module_id'
        );
    }

    public function progress()
    {
        return $this->hasMany(
            ModuleProgress::class,
            'module_id'
        );
    }
}