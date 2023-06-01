<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasFactory;

    protected $table = 'favorites' ;

    protected $fillable = [
        'user_id' ,
        'property_type' ,
        'car_id' ,
        'estate_id'
    ] ;


}
