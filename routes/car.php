<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
//Add Car
route::post('Add_Cars', [\App\Http\Controllers\CarController::class , 'store_car']) ;

//Get the cars for any user
route::get('Get_User_Cars/{id}' ,[\App\Http\Controllers\CarController::class , 'Get_User_Cars'] ) ;

//remove car posts
route::get('Remove_Car/{id}' , [\App\Http\Controllers\CarController::class , 'remove_car_post']) ;

//Home Screen For Cars
route::get('Car_Home' , [\App\Http\Controllers\HomeController::class,'Car_Home']) ;

//Get specific car
route::get('Get_Car/{id}' , [\App\Http\Controllers\CarController::class,'get_car']) ;
