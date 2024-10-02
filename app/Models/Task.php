<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "status",
        "soft_del",
        'filename',
        'filepath',
        'size',
        'type',
    ];

    protected $table = 'task';

    protected $primaryKey  = 'task_id';
    public function files()
    {
        return $this->hasMany(Task::class, 'task_id', 'task_id');
    }

}
