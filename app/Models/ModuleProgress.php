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
        'module_id',
        'workshop_id',
        'status',
        'progress_percentage',
        'started_at',
        'completed_at'
    ];

    protected function casts(): array
    {
        return [
            'progress_percentage' => 'integer',
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
