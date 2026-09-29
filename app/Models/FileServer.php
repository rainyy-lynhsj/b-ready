<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileServer extends Model
{
    use HasFactory;

    protected $table = 'file_servers';

    protected $fillable = [
        'title',
        'file_path',
        'file_type',
    ];
}