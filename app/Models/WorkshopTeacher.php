<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkshopTeacher extends Model
{
    use HasFactory;

    protected $table = 'workshop_teachers';

    protected $fillable = [
        'workshop_id',
        'teacher_id',
        'user_id',
        'status',
        'joined_at'
    ];

    protected static function booted()
    {
        static::saving(function ($model) {
            if (empty($model->user_id) && ! empty($model->teacher_id)) {
                $model->user_id = $model->teacher_id;
            } elseif (empty($model->teacher_id) && ! empty($model->user_id)) {
                $model->teacher_id = $model->user_id;
            }
        });
    }

    protected function casts(): array
    {
        return [
            'joined_at' => 'datetime',
        ];
    }

    public function workshop()
    {
        return $this->belongsTo(
            Workshop::class,
            'workshop_id'
        );
    }

    public function teacher()
    {
        return $this->belongsTo(
            User::class,
            'teacher_id'
        );
    }
}
