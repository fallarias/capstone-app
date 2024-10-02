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
        'Office_Done',
        'status',
    ];

    protected $table = 'tbl_transaction';
    protected $primaryKey = 'transaction_id';

    
}
