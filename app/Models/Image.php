<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Image extends Model
{
    use HasFactory;

    protected $table = 'images' ;

    protected $fillable = [
        'name' ,
        'car_id' ,
        'estate_id' ,
        'property_type'
    ] ;

    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    public function delete()
    {
        $imagePath = str_replace('/storage', '', parse_url($this->name, PHP_URL_PATH));
        Storage::delete($imagePath);
        parent::delete();
    }
}
