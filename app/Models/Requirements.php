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
        'staff_id',
        'stop_transaction',
        'resume_transaction'
    ];

    protected $table = 'requirements';

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id'); // Adjust 'user_id' if your column name differs
    }

    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id', 'user_id'); // User linked to staff_id
    }
}
