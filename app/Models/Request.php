<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
    use HasFactory;

    protected $fillable= [
        'client_fname',
        'client_lname',
        'client_mname',
        'Office_use',
        'Request_type',
        'Reason_of_request',
        'client_id',
        'transaction_id',
        'supplier_id',


    ];
    protected $table = 'tbl_request';
    protected $primaryKey = 'request_id';
}
