<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        //id= 1
        User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => Hash::make('123456789'),
            'phone_number' => '1234567890',
            'image' => 'default.jpg'
        ]);
        //id= 2
        User::create([
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'password' => Hash::make('123456789'),
            'phone_number' => '0987654321',
            'image' => 'default.jpg'
        ]);

        //id= 3
        User::create([
            'name' => 'Bob Smith',
            'email' => 'bob@example.com',
            'password' => Hash::make('123456789'),
            'phone_number' => '5555555555',
            'image' => 'default.jpg'
        ]);

        //id= 4
        User::create([
            'name' => 'modar salem',
            'email' => 'modar16666@gmail.com',
            'password' => Hash::make('123456789'),
            'phone_number' => '5555555555',
            'image' => 'default.jpg'
        ]);

        //id= 5
        User::create([
            'name' => 'sham salem',
            'email' => 'sham16666@gmail.com',
            'password' => Hash::make('123456789'),
            'phone_number' => '5555555555',
            'image' => 'default.jpg'
        ]);
    }
}
