<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'classroom_implementation_id',
        'student_identifier',
        'score',
        'percentage',
        'result',
    ];

    protected function casts(): array
    {
        return [
            'score' => 'decimal:2',
            'percentage' => 'decimal:2',
        ];
    }

    public function classroomImplementation()
    {
        return $this->belongsTo(
            ClassroomImplementation::class,
            'classroom_implementation_id'
        );
    }
}
