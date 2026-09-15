<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassroomImplementation extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'workshop_id',
        'classroom_package_id',
        'implementation_date',
        'status',
        'students_participated',
        'students_completed_assessment',
        'students_passed',
        'students_failed',
        'teacher_reflection',
        'remarks',
        'supporting_record',
    ];

    protected function casts(): array
    {
        return [
            'implementation_date' => 'date',
            'stundents_participated' => 'integer',
            'students_completed_assessment' => 'integer',
            'students_passed' => 'integer',
            'students_failed' => 'integer',
        ];
    }

    public function teacher()
    {
        return $this->belongsTo(
            User::class,
            'teacher_id',
        );
    }

    public function workshop()
    {
        return $this->belongsTO(
            Workshop::class,
            'workshop_id',
        );
    }

    public function classroomPackage()
    {
        return $this->belongsTo(
            ClassroomPackage::class,
            'classroom_implemantation_id',
        );
    }

    public function studentResults()
    {
        return $this->hasMany(
            StudentResult::class,
            'classroom_implementation_id',
        );
    }
}
