<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class verify_email extends Model
{
    use HasFactory;

    protected $table = 'verify' ;

    protected $fillable = [
        'email',
        'code'
    ];
}
