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


//Add Estate
route::post('Add_Estate', [\App\Http\Controllers\EstateController::class , 'store_estate']) ;

//Get  Estates for any user
route::get('Get_User_Estate/{id}' ,[\App\Http\Controllers\EstateController::class , 'Get_User_Estates'] ) ;

//Get Specific Estate
route::get('Get_Estate/{id}' , [\App\Http\Controllers\EstateController::class,'get_estate']) ;

//Remove Estate
route::get('Remove_Estate/{id}' , [\App\Http\Controllers\EstateController::class , 'remove_estate_post']) ;

//Update Estate or Cars
Route::post('Update_Post' , [\App\Http\Controllers\EstateController::class , 'update_post']) ;

//Home route
route::get('Estate_Home' , [\App\Http\Controllers\HomeController::class,'Estate_Home']) ;




