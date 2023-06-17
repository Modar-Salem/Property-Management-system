<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;

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
        return $this->hasMany(Favorite::class  ) ;
    }

    public function estates(): HasMany
    {
        return $this->hasMany(Estate::class , 'owner_id') ;
    }

    public function delete()
    {
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
    public function updatePassword($newPassword)
    {
        $this->password = Hash::make($newPassword);
        $this->save();
    }
}
