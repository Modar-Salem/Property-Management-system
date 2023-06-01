<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

route::post('search' , [\App\Http\Controllers\Search_Filter::class , 'Search']);

route::post('filter' , [\App\Http\Controllers\Search_Filter::class , 'Filter']);

route::post('Add_Estate', [\App\Http\Controllers\Posts::class , 'store_estate']) ;

route::post('Add_Cars', [\App\Http\Controllers\Posts::class , 'store_car']) ;

route::get('Get_User_Estate/{id}' ,[\App\Http\Controllers\Posts::class , 'Get_User_Estates'] ) ;

route::get('Get_User_Cars/{id}' ,[\App\Http\Controllers\Posts::class , 'Get_User_Cars'] ) ;

route::get('Remove_Estate/{id}' , [\App\Http\Controllers\Posts::class , 'remove_estate_posts']) ;

route::get('Remove_Car/{id}' , [\App\Http\Controllers\Posts::class , 'remove_car_posts']) ;

route::get('Estate_Home' , [\App\Http\Controllers\HomeController::class,'Estate_Home']) ;

route::get('Car_Home' , [\App\Http\Controllers\HomeController::class,'Car_Home']) ;
