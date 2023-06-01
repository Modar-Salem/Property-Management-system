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
        Schema::create('cars', function (Blueprint $table) {
            $table->id();

            $table->foreignId('owner_id')->constrained('users')
            ->onDelete('cascade');

            $table->enum('operation_type' , ['sale' , 'rent']) ;
            $table->enum('transmission_type' , ['auto', 'manual']) ;
            $table->enum('driving_force' , ['four wheel' , 'normal']) ;
            $table->enum('fuel_type' , ['gaz' ,'diesel']) ;
            $table->enum('status' , ['new' , 'used']) ;

            $table->enum('brand' , ['Acura', 'Alfa Romeo', 'Aston Martin', 'Audi', 'BMW', 'Bentley', 'Bugatti', 'Buick', 'Cadillac', 'Chevrolet', 'Chrysler', 'Citroen', 'Cooper', 'Dacia', 'Daewoo',
                'Dodge', 'Ferrari', 'Fiat', 'Ford', 'GMC', 'Geely', 'General Motors', 'Genesis', 'Holden', 'Honda', 'Hummer', 'Hyundai', 'Infiniti', 'Isuzu', 'Jaguar', 'Jeep', 'Kia', 'Koenigsegg', 'Lamborghini',
                'Lancia', 'Land Rover', 'Lexus', 'Lincoln', 'Lotus', 'Maserati', 'Mazda', 'McLaren', 'Mercedes-Benz', 'Mercury', 'Mini', 'Mitsubishi', 'Nissan', 'Opel', 'Pagani', 'Peugeot', 'Polestar', 'Pontiac',
                'Porsche', 'Ram', 'Renault', 'Rivian', 'Rolls-Royce', 'Saab', 'Saturn', 'Scion', 'Seat', 'Skoda', 'Smart', 'Subaru', 'Suzuki', 'Tata Motors', 'Tesla', 'Toyota', 'Volkswagen', 'Volvo']) ;

            $table->string('secondary_brand')->nullable() ;


            $table->enum('governorate' , [ 'Aleppo','Al-Ḥasakah','Al-Qamishli','Al-Qunayṭirah','Al-Raqqah','Al-Suwayda','Damascus','Darʿa','Dayr al-Zawr','Ḥamah','Homs','Idlib','Latakia' , 'Rif Dimashq']);

            $table->enum('locationInDamascus', ['Ancient City (Old City)', 'Barzeh', 'Dummar', 'Jobar', 'Qanawat', 'Kafr Souseh', 'Mezzeh',
                'Al-Midan', 'Muhajreen', 'Qaboun', 'Qadam', 'Rukn ad-Din', 'Al-Salihiyah', 'Sarouja', 'Al-Shaghour', 'Yarmouk' , 'Jaramana'])->nullable() ;

            $table->string('address')->nullable() ;

            $table->enum('color', [['Blue', 'Red', 'Green', 'Black', 'White', 'Yellow', 'Pink', 'Purple', 'Orange', 'Gray', 'Brown', 'Beige', 'Turquoise'
                , 'Gold', 'Silver', 'Magenta', 'Navy', 'Teal', 'Maroon', 'Lavender', 'Cream', 'Olive', 'Sky blue', 'Coral', 'Indigo', 'Charcoal', 'Rust', 'Mint green', 'Mustard', 'Champagne']])->nullable() ;

            $table->text('description')->nullable();

            $table->double('price')->unsigned() ;
            $table->integer('year')->unsigned()->min(1940)->max(2023) ;
            $table->integer('kilometers')->unsigned() ;

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void

    {
        Schema::dropIfExists('cars');
    }
};
