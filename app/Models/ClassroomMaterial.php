<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassroomMaterial extends Model
{
    use HasFactory;

    protected $fillable = [
        'classroom_package_id',
        'title',
        'material_type',
        'file_path',
        'original_filename',
        'description',
        'sequence',
    ];

    public function classroomPackage()
    {
        return $this->belongsTo(
            ClassroomPackage::class,
            'classroom_package_id',
        );
    }
}
