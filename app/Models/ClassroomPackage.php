<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassroomPackage extends Model
{
    use HasFactory;

    protected $fillable = [
        'workshop_id',
        'trainer_id',
        'title',
        'description',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
        ];
    }

    public function workshop()
    {
        return $this->belongsTo(
            Workshop::class,
            'workshop_id'
        );
    }

    public function trainer()
    {
        return $this->belongsTo(
            User::class,
            'trainer_id',
        );
    }

    public function materials()
    {
        return $this->hasMany(
            ClassroomMaterial::class,
            'classroom_package_id',
        )->orderBy('sequence');
    }

    public function implementations()
    {
        return $this->hasMany(
            ClassroomImplementation::class,
            'classroom_package_id'
        );
    }
}
