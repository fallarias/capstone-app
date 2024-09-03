<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    protected $fillable = [
        'filename',
        'filepath',
        'size',
        'type',

    ];
    protected $table = 'files';

    protected $primaryKey = 'file_id';
}
