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
        'status',
        'joined_at'
    ];

    protected function casts(): array
    {
        return[
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
