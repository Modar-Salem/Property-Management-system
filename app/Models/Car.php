<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Image;
use Illuminate\Support\Facades\Storage;

class Car extends Model
{
    use HasFactory;

    protected $table = 'cars';

    protected $fillable = [
        'owner_id',
        'operation_type',
        'transmission_type',
        'brand',
        'secondary_brand',
        'governorate',
        'locationInDamascus',
        'color',
        'description',
        'price',
        'year',
        'kilometers' ,
        'address' ,
        'fuel_type' ,
        'status' ,
        'driving_force'
    ];

    public function images() : HasMany
    {
        return $this->hasMany(\App\Models\Image::class , 'car_id') ;
    }
    public function owner()
    {
        return $this->belongsTo(User::class) ;
    }

    public function delete()
    {
        // delete images
        $this->images->each(function ($image) {
            $imagePath = str_replace('/storage', '', parse_url($image->name, PHP_URL_PATH));
            Storage::delete($imagePath);
            $image->delete();
        });

        // delete the car
        parent::delete();
    }
}
