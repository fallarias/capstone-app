<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;
    protected $fillable= [
        'supplier_fname',
        'supplier_lname',
        'supplier_mname',
        'type_of_service',
        'service_desc',
        'address'

    ];
    protected $table = 'tbl_supplier';
    protected $primaryKey = 'supplier_id';
}




