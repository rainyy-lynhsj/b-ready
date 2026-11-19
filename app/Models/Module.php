
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
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function materials()
    {
        return $this->hasMany(Material::class, 'module_id')
            ->orderBy('sequence');
    }

    public function assessments()
    {
        return $this->hasMany(Assessment::class, 'module_id');
    }

    public function quiz()
    {
        return $this->hasOne(Assessment::class, 'module_id')
            ->whereNull('workshop_id');
    }

    /**
     * Workshop assignments containing this module.
     */
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
