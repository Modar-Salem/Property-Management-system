<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;
use Faker\Factory as Faker;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $fillable = [
        'name',
        'email',
        'google_id',
        'password',
        'phone_number',
        'image' ,
        'facebook_URL',
        'instagram_URL',
        'twitter_URL'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function cars(): HasMany
    {
        return $this->hasMany(Car::class , 'owner_id');
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class ,'user_id' ) ;
    }

    public function estates(): HasMany
    {
        return $this->hasMany(Estate::class , 'owner_id') ;
    }

    public function delete()
    {
        if($this->image !=null)
        {
            $imagePath = str_replace('/storage', '', parse_url($this->image, PHP_URL_PATH));
            Storage::delete($imagePath) ;
        }

        // delete images for all cars
        $this->cars->each(function ($car) {
            $car->images->each(function ($image) {
                $imagePath = str_replace('/storage', '', parse_url($image->name, PHP_URL_PATH));
                Storage::delete($imagePath);
                $image->delete();
            });
            $car->delete();
        });

        $this->estates->each(function ($estate) {
            $estate->images->each(function ($image) {
                $imagePath = str_replace('/storage', '', parse_url($image->name, PHP_URL_PATH));
                Storage::delete($imagePath);
                $image->delete();
            });
            $estate->delete();
        });

        // delete the user
        parent::delete();

    }

    public function favoriteEstates(): BelongsToMany
    {
        return $this->belongsToMany(Estate::class, 'favorites', 'user_id', 'estate_id');
    }

    public function favoriteCar(): BelongsToMany
    {
        return $this->belongsToMany(Car::class, 'favorites', 'user_id', 'car_id');
    }

    public function updatePassword($newPassword)
    {
        $this->password = Hash::make($newPassword);
        $this->save();
    }

    public function isCarFavorite(Car $car)
    {
        return $this->favorites()->where('car_id', $car->id)->exists();
    }

    public function isEstateFavorite(Estate $estate)
    {
        return $this->favorites()->where('estate_id', $estate->id)->exists();
    }

    public function generateFakeData()
    {
        $faker = Faker::create();
        $this->name = $faker->name;
        $this->email = $faker->email;
        $this->password = Hash::make(123456789);
        $this->phone_number = $faker->phoneNumber;
        $this->image = $faker->imageUrl;
        $this->facebook_URL = $faker->url;
        $this->instagram_URL = $faker->url;
        $this->twitter_URL = $faker->url;

    }


}
