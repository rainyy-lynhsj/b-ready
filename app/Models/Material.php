<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    protected $fillable = [
        'module_id',
        'title',
        'type',
        'file_path',
        'external_url',
        'description',
    ];

    /**
     * The module this material belongs to.
     */
    public function module()
    {
        return $this->belongsTo(Module::class, 'module_id');
    }

}
