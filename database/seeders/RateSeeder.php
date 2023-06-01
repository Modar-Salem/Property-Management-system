<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RateSeeder extends Seeder
{
    /**
    * Run the database seeds.
    */
    public function run(): void
    {

            \App\Models\Rate::create([
                'user_id' => 1,
                'property_type' => 'estate',
                'car_id' => null,
                'estate_id' => 2,
                'rate' => 4
            ]);

            \App\Models\Rate::create([
                'user_id' => 3,
                'property_type' => 'car',
                'car_id' => 4,
                'estate_id' => null,
                'rate' => 3
            ]);

            \App\Models\Rate::create([
                'user_id' => 2,
                'property_type' => 'estate',
                'car_id' => null,
                'estate_id' => 1,
                'rate' => 5
            ]);

            \App\Models\Rate::create([
                'user_id' => 4,
                'property_type' => 'estate',
                'car_id' => null,
                'estate_id' => 3,
                'rate' => 2
            ]);

            \App\Models\Rate::create([
                'user_id' => 1,
                'property_type' => 'car',
                'car_id' => 2,
                'estate_id' => null,
                'rate' => 4
            ]);

            \App\Models\Rate::create([
                'user_id' => 3,
                'property_type' => 'car',
                'car_id' => 5,
                'estate_id' => null,
                'rate' => 2
            ]);

            \App\Models\Rate::create([
                'user_id' => 2,
                'property_type' => 'estate',
                'car_id' => null,
                'estate_id' => 1,
                'rate' => 5
            ]);

            \App\Models\Rate::create([
                'user_id' => 4,
                'property_type' => 'estate',
                'car_id' => null,
                'estate_id' => 3,
                'rate' => 2
            ]);
        }

}
