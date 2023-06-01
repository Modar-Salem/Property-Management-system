<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Estate extends Model
{
    use HasFactory;

    protected $table = 'estates';

    protected $fillable = [
        'owner_id' ,
        'estate_type',
        'operation_type',
        'governorate',
        'locationInDamascus',
        'description',
        'price',
        'space',
        'address' ,
        'status',
        'level',
        'beds' ,
        'baths' ,
        'garage'
    ];


    public function images(): HasMany
    {
        return $this->hasMany(Image::class , 'estate_id') ;
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

        // delete the estate
        parent::delete();
    }
}
