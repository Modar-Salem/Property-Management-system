<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('google_id')->nullable() ;

            $table->string('name');

            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();

            $table->string('password')->nullable();

            $table->string('phone_number')->nullable();
            $table->string('image')->nullable();

            $table->enum('role' , ['normal','admin'])->default('normal') ;

            $table->string('facebook_URL')->nullable() ;
            $table->string('instagram_URL')->nullable() ;
            $table->string('twitter_URL')->nullable() ;

            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
