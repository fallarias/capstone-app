<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Requirements extends Model
{
    use HasFactory;

    protected $fillable = [
        'message',
        'transaction_id',
        'user_id',
        'department',
        'stop_transaction',
        'resume_transaction'
    ];

    protected $table = 'requirements';
}
