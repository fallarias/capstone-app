<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Audit extends Model
{
    use HasFactory;

    protected $fillable = [
        'office_name',
        'start',
        'finished',
        'deadline',
        'user_id',
        'task_id',
        'transaction_id',
        'email_reminder_sent',
        'task'
    ];
    
    protected $table = 'audit_trails';
    protected $primaryKey = 'audit_id';
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id'); // Adjust 'user_id' if your column name differs
    }
}
