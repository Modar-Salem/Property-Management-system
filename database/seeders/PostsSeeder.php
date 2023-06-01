<?php

namespace Database\Seeders;

use App\Models\Car;
use App\Models\Estate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PostsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        //Owner 1 : have 3 posts
        //Owner 2 : have 2 posts
        //Owner 3 : have 1 posts
        //Owner 4 : have 1 posts
        //Owner 5 : have 1 posts
             Estate::create([
                'owner_id' => 1,
                'estate_type' => 'Apartment',
                'operation_type' => 'Sale',
                'location' => 'Damascus',
                'locationInDamascus' => 'Mazzeh',
                'description' => 'A beautiful apartment in a prime location',
                'price' => 100000,
                'space' => 100
            ]);

            Estate::create([
                'owner_id' => 1,
                'estate_type' => 'Villa',
                'operation_type' => 'Rent',
                'location' => 'Aleppo',
                'locationInDamascus' => null,
                'description' => 'A luxurious villa with a swimming pool',
                'price' => 5000,
                'space' => 500
            ]);


            Estate::create([
                'owner_id' => 1,
                'estate_type' => 'Apartment',
                'operation_type' => 'Sale',
                'location' => 'Damascus',
                'locationInDamascus' => 'Mazzeh',
                'description' => 'A beautiful apartment ',
                'price' => 200000,
                'space' => 300
            ]);

            Estate::create([
                'owner_id' => 2,
                'estate_type' => 'Villa',
                'operation_type' => 'Rent',
                'location' => 'Aleppo',
                'locationInDamascus' => null,
                'description' => 'A luxurious villa with a swimming pool',
                'price' => 5000,
                'space' => 500
            ]);


        Car::create([
                'owner_id' => 2,
                'operation_type' => 'Sale',
                'transmission_type' => 'Automatic',
                'brand' => 'Toyota',
                'secondary_brand' => 'Camry',
                'location' => 'Damascus',
                'locationInDamascus' => 'Mezzeh',
                'color' => 'Black',
                'description' => 'A well-maintained car in great condition',
                'price' => 50000,
                'year' => 2018,
                'kilometers' => 20000
            ]);

            Car::create([
                'owner_id' => 3,
                'operation_type' => 'Rent',
                'transmission_type' => 'Manual',
                'brand' => 'Kia',
                'secondary_brand' => 'Rio',
                'location' => 'Aleppo',
                'locationInDamascus' => null,
                'color' => 'White',
                'description' => 'A fuel-efficient car perfect for city driving',
                'price' => 10000,
                'year' => 2015,
                'kilometers' => 50000
            ]);

            Car::create([
                'owner_id' => 4,
                'operation_type' => 'Sale',
                'transmission_type' => 'Automatic',
                'brand' => 'Toyota',
                'secondary_brand' => 'Corola',
                'location' => 'Damascus',
                'locationInDamascus' => 'Mezzeh',
                'color' => 'Black',
                'description' => 'A well-maintained car in great condition',
                'price' => 50000,
                'year' => 2018,
                'kilometers' => 20000
            ]);

            Car::create([
                'owner_id' => 4,
                'operation_type' => 'Rent',
                'transmission_type' => 'Manual',
                'brand' => 'Kia',
                'secondary_brand' => 'cerato',
                'location' => 'Aleppo',
                'locationInDamascus' => null,
                'color' => 'White',
                'description' => 'A fuel-efficient car perfect for city driving',
                'price' => 10000,
                'year' => 2015,
                'kilometers' => 50000
        ]);

    }
}
