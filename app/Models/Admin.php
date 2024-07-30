<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    use HasFactory;
    protected $fillable = [
        'email',
        'password',
        'account_type',
        'passnohash',
    ];

    protected $table = 'tbl_admin';
}
class Create extends Model
{
    use HasFactory;
    protected $fillable = [
        'office_name',
        'password',
        'account_type',
        'passnohash',
    ];

    protected $table = 'users';
}
