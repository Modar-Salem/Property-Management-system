<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FavoriteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Favorite::create([
        'user_id' => 1,
        'property_type' => 'car',
        'car_id' => 1,
        'estate_id' => null
    ]);

        \App\Models\Favorite::create([
            'user_id' => 2,
            'property_type' => 'estate',
            'car_id' => null,
            'estate_id' => 1
        ]);
    }
}
