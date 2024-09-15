<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserOtp extends Model
{
    use HasFactory;

    protected $fillable = [
        'otp_type',
        'number_or_email',
        'otp',
        'expire_at',
    ];

    protected $table = 'otp';

    protected $primaryKey = 'id';
}
