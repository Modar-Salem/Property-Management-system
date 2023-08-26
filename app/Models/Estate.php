<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Faker\Factory as Faker;
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

    public function generateFakeData()
    {
        $faker = Faker::create();
        $this->owner_id = $faker->numberBetween(1, 100);
        $this->estate_type = $faker->randomElement(['land', 'apartment' , 'villa' , 'commercial shops' , 'warehouses' ,'commercial real estate' ]);
        $this->operation_type = $faker->randomElement(['sell','rent']);
        $this->governorate = $faker->randomElement(['Aleppo','Al-Ḥasakah','Al-Qamishli','Al-Qunayṭirah','Al-Raqqah','Al-Suwayda','Damascus','Daraa','Dayr al-Zawr','Ḥamah','Homs','Idlib','Latakia' , 'Rif Dimashq']);
        $this->locationInDamascus = $faker->randomElement(['Ancient City (Old City)', 'Barzeh', 'Dummar', 'Jobar', 'Qanawat', 'Kafr Souseh', 'Mezzeh',
            'Al-Midan', 'Muhajreen', 'Qaboun', 'Qadam', 'Rukn ad-Din', 'Al-Salihiyah', 'Sarouja', 'Al-Shaghour', 'Yarmouk' , 'Jaramana']);

        $this->description = $faker->paragraph;
        $this->price = $faker->numberBetween(1000, 100000);
        $this->space = $faker->numberBetween(50, 2000);
        $this->address = $faker->address;
        $this->status = $faker->randomElement(['barebones'  , 'furnished']);
        $this->level = $faker->numberBetween(1, 10);
        $this->beds = $faker->numberBetween(1, 5);
        $this->baths = $faker->numberBetween(1, 5);
        $this->garage = $faker->numberBetween(1, 4);


    }
}
