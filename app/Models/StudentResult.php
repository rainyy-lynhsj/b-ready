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
        'total_questions',
        'percentage',
        'status',
        'result',
    ];

    protected static function booted()
    {
        static::saving(function ($model) {
            if (empty($model->total_questions) || (int)$model->total_questions <= 0) {
                $model->total_questions = 100;
            }

            if (empty($model->percentage) && (float)$model->total_questions > 0) {
                $model->percentage = round(((float)$model->score / (float)$model->total_questions) * 100, 2);
            }

            if (empty($model->status)) {
                $model->status = ((float)$model->percentage >= 70.0) ? 'Passed' : 'Failed';
            }

            if (empty($model->result)) {
                $model->result = strtolower($model->status);
            }
        });
    }

    protected function casts(): array
    {
        return [
            'score' => 'decimal:2',
            'total_questions' => 'integer',
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
