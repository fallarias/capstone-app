<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class qrcode extends Model
{
    use HasFactory;
    protected $fillable= [
        'staff_id',
    ];
    protected $table = 'tbl_qrcode';
    protected $primaryKey = 'qrcode_id';
}
