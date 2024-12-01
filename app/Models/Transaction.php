<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'task_id',
        'Total_Office_of_Request',
        'deadline',
        'Office_Done',
        'status',
        'deadline',
    ];

    protected $table = 'tbl_transaction';
    protected $primaryKey = 'transaction_id';

    // Transaction.php
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id'); // Adjust 'user_id' if your column name differs
    }
    public function task()
    {
        return $this->belongsTo(Task::class, 'task_id'); // Adjust 'user_id' if your column name differs
    }

}
