<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Faker\Factory as Faker;

class Rate extends Model
{
    use HasFactory;

    protected $table = 'rates' ;

    protected $fillable = [
        'user_id' ,
        'property_type' ,
        'car_id' ,
        'estate_id',
        'rate'
    ] ;

    public function generateFakeData()
    {
        $faker = Faker::create();

        $this->user_id = $faker->numberBetween(1, 100);
        $this->property_type = $faker->randomElement(['estate', 'car']);

        if ($this->property_type == 'estate') {
            $this->estate_id = $faker->numberBetween(1, 500);
            $this->car_id = null;
        } else if ($this->property_type == 'car') {
            $this->car_id = $faker->numberBetween(1, 500);
            $this->estate_id = null;
        }
        $this->rate = $faker->numberBetween(1, 5);
    }

}
