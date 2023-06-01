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
        Schema::create('rates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')
            ->onDelete('cascade');;
            $table->enum('property_type' , ['estate' , 'car']) ;
            $table->foreignId('car_id')->constrained('cars')->nullable()
                ->onDelete('cascade');;
            $table->foreignId('estate_id')->constrained('estates')->nullable()
                ->onDelete('cascade');;
            $table->integer('rate')->min(1)->max(5);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rates');
    }
};
