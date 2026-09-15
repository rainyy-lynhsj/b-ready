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
        'material_type',
        'file_path',
        'original_filename',
        'description',
        'sequence',
    ];

    /**
     * The module this material belongs to.
     */
    public function module()
    {
        return $this->belongsTo(Module::class, 'module_id');
    }

}
