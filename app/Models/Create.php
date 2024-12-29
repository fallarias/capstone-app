<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Create extends Model
{
    use HasFactory;

    protected $fillable = [
        'Office_name',
        'Office_task',
        'New_alloted_time',
        'user_id',
        'task_id',
        'soft_del',
    ];
    protected $table = 'tbl_created_task';
    protected $primaryKey = 'create_id';
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id'); // Adjust 'user_id' if your column name differs
    }
}


