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
        Schema::create('estates', function (Blueprint $table) {
            $table->id();

            $table->foreignId('owner_id')->constrained('users')
                ->onDelete('cascade');

            $table->enum('estate_type' , ['land', 'apartment' , 'villa' , 'commercial shops' , 'warehouses' ,'commercial real estate' ]);
            $table->enum('operation_type', ['sale','rent']);
            $table->enum('status' , ['barebones'  , 'furnished']) ;

            $table->enum('governorate' , ['Aleppo','Al-Ḥasakah','Al-Qamishli','Al-Qunayṭirah','Al-Raqqah','Al-Suwayda','Damascus','Darʿa','Dayr al-Zawr','Ḥamah','Homs','Idlib','Latakia' , 'Rif Dimashq']);

            $table->enum('locationInDamascus' , ['Ancient City (Old City)', 'Barzeh', 'Dummar', 'Jobar', 'Qanawat', 'Kafr Souseh', 'Mezzeh',
                'Al-Midan', 'Muhajreen', 'Qaboun', 'Qadam', 'Rukn ad-Din', 'Al-Salihiyah', 'Sarouja', 'Al-Shaghour', 'Yarmouk' , 'Jaramana'])->nullable() ;

            $table->string('address')->nullable() ;

            $table->text('description') ->nullable();


            $table->double('price')->unsigned();
            $table->double('space')->unsigned();

            $table->integer('beds')->nullable() ;
            $table->integer('baths')->nullable() ;
            $table->integer('garage')->nullable() ;
            $table->integer('level')->nullable() ;

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estates');
    }
};
