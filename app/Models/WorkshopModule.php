<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkshopModule extends Model
{
    use HasFactory;

    protected $table = 'workshop_modules';

    protected $fillable = [
        'workshop_id',
        'module_id',
        'sequence'
    ];

    public function workshop()
    {
        return $this->belongsTo(
            Workshop::class,
            'workshop_id'
        );
    }

    public function module()
    {
        return $this->belongsTo(
            Module::class,
            'module_id'
        );
    }
}
