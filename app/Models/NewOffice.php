<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewOffice extends Model
{
    use HasFactory;

    protected $fillable = [ 'office_name' ];
    protected $table = 'offices';

    protected $primaryKey = 'id';

}
