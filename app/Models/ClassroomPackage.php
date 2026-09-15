<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassroomPackage extends Model
{
    use HasFactory;

    protected $fillable = [
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
    
}
