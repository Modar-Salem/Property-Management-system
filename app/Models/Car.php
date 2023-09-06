<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Faker\Factory as Faker;

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

    public function favorites()
    {
        return $this->hasMany(Favorite::class, 'car_id');
    }

    public function images() : HasMany
    {
        return $this->hasMany(\App\Models\Image::class , 'car_id') ;
    }
    public function owner()
    {
        return $this->belongsTo(User::class) ;
    }
    public function rates(): HasMany
    {
        return $this->hasMany(Rate::class, 'car_id');
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


    public function generateFakeData()
    {
        $faker = Faker::create();
        $this->owner_id = $faker->numberBetween(1, 100);

        $this->operation_type = $faker->randomElement(['sell', 'rent ']);

        $this->transmission_type = $faker->randomElement(['manually', 'automatic']);

        $this->brand = $faker->randomElement(['Acura', 'Alfa Romeo', 'Aston Martin', 'Audi', 'BMW', 'Bentley', 'Bugatti', 'Buick', 'Cadillac', 'Chevrolet', 'Chrysler', 'Citroen', 'Cooper', 'Dacia', 'Daewoo',
            'Dodge', 'Ferrari', 'Fiat', 'Ford', 'GMC', 'Geely', 'General Motors', 'Genesis', 'Holden', 'Honda', 'Hummer', 'Hyundai', 'Infiniti', 'Isuzu', 'Jaguar', 'Jeep', 'Kia', 'Koenigsegg', 'Lamborghini',
            'Lancia', 'Land Rover', 'Lexus', 'Lincoln', 'Lotus', 'Maserati', 'Mazda', 'McLaren', 'Mercedes-Benz', 'Mercury', 'Mini', 'Mitsubishi', 'Nissan', 'Opel', 'Pagani', 'Peugeot', 'Polestar', 'Pontiac',
            'Porsche', 'Ram', 'Renault', 'Rivian', 'Rolls-Royce', 'Saab', 'Saturn', 'Scion', 'Seat', 'Skoda', 'Smart', 'Subaru', 'Suzuki', 'Tata Motors', 'Tesla', 'Toyota', 'Volkswagen', 'Volvo']) ;

        $this->secondary_brand = $faker->word;

        $this->governorate = $faker->randomElement(['Aleppo','Al-Ḥasakah','Al-Qamishli','Al-Qunayṭirah','Al-Raqqah','Al-Suwayda','Damascus','Daraa','Dayr al-Zawr','Ḥamah','Homs','Idlib','Latakia' , 'Rif Dimashq']);

        $this->locationInDamascus = $faker->randomElement(['Ancient City (Old City)', 'Barzeh', 'Dummar', 'Jobar', 'Qanawat', 'Kafr Souseh', 'Mezzeh',
            'Al-Midan', 'Muhajreen', 'Qaboun', 'Qadam', 'Rukn ad-Din', 'Al-Salihiyah', 'Sarouja', 'Al-Shaghour', 'Yarmouk' , 'Jaramana']);

        $this->color = $faker->randomElement(['Blue', 'Red', 'Green', 'Black', 'White', 'Yellow', 'Pink', 'Purple', 'Orange', 'Gray', 'Brown', 'Beige', 'Turquoise'
            , 'Gold', 'Silver', 'Magenta', 'Navy', 'Teal', 'Maroon', 'Lavender', 'Cream', 'Olive', 'Sky blue', 'Coral', 'Indigo', 'Charcoal', 'Rust', 'Mint green', 'Mustard', 'Champagne']);

        $this->description = $faker->paragraph;

        $this->price = $faker->numberBetween(1000, 100000);

        $this->year = $faker->numberBetween(1960 , 2023);

        $this->kilometers = $faker->numberBetween(1000, 50000);

        $this->address = $faker->address;

        $this->fuel_type = $faker->randomElement(['gasoline', 'diesel' , 'hybrid', 'electric']);

        $this->status = $faker->randomElement(['new' , 'used']);

        $this->driving_force = $faker->randomElement(['4WD', 'FWD', 'RWD']);


    }
}
