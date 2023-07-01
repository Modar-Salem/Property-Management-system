<?php

// database/seeders/ModelSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use App\Models\Car;
use App\Models\Estate;
use App\Models\User;
use App\Models\Rate;
use App\Models\Favorite;

class generateFakeData extends Seeder
{
    public function run()
    {
        for ($i = 0; $i < 10; $i++)
        {
            $user = new User();
            $user->generateFakeData();
            $user->save() ;
        }

        for ($i = 0; $i < 20; $i++) {
            $car = new Car();
            $car->generateFakeData();
            $car->save();
        }

        for ($i = 0; $i < 20; $i++) {
            $estate = new Estate();
            $estate->generateFakeData();
            $estate->save();
        }

        for ($i = 0; $i < 50; $i++) {
            $rate = new Rate();
            $rate->generateFakeData();
            $rate->save();
        }

        for ($i = 0; $i < 50; $i++) {
            $favorite = new Favorite();
            $favorite->generateFakeData();
            $favorite->save();
        }
    }
}
