<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModuleProgress extends Model
{
    use HasFactory;

    protected $table = 'module_progress';

    protected $fillable = [
        'teacher_id',
        'user_id',
        'module_id',
        'workshop_id',
        'progress',
        'status',
        'started_at',
        'completed_at'
    ];

    protected static function booted()
    {
        static::saving(function ($model) {
            if (empty($model->user_id) && ! empty($model->teacher_id)) {
                $model->user_id = $model->teacher_id;
            } elseif (empty($model->teacher_id) && ! empty($model->user_id)) {
                $model->teacher_id = $model->user_id;
            }

            if ($model->status === 'completed' && ($model->progress === null || $model->progress < 100)) {
                $model->progress = 100;
            }
        });
    }

    protected function casts(): array
    {
        return [
            'progress' => 'integer',
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    public function teacher()
    {
        return $this->belongsTo(
            User::class,
            'teacher_id'
        );
    }

    public function module()
    {
        return $this->belongsTo(
            Module::class,
            'module_id'
        );
    }

    public function workshop()
    {
        return $this->belongsTo(
            Workshop::class,
            'workshop_id'
        );
    }
}
