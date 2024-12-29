<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewOffice extends Model
{
    use HasFactory;

    protected $fillable = [ 

        'target_department',
        'message',
        'department'

     ];
    protected $table = 'offices';

    protected $primaryKey = 'id';

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id'); // Adjust 'user_id' if your column name differs
    }   

}
