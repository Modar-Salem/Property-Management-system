<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;
    protected $fillable = [
        'sender_id',
        'receiver_id',
        'message',
    ];

    // Define relationships if needed
     public function sender()
     {
         return $this->belongsTo(User::class, 'sender_id');
     }

     public function receiver()
     {
         return $this->belongsTo(User::class, 'receiver_id');
     }
}
