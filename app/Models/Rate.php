<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    use HasFactory;
    protected $fillable= [
        'transaction_id',
        'user_id',
        'score',

    ];

    protected $table = 'rate_staff';
    protected $primaryKey = 'id';

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id'); // Adjust 'user_id' if your column name differs
    } 
}
