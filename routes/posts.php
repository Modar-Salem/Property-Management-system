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


//Add Post (Car And Estate)
route::post('Add_Estate', [\App\Http\Controllers\PostsController::class , 'store_estate']) ;

route::post('Add_Cars', [\App\Http\Controllers\PostsController::class , 'store_car']) ;


//Get Posts
route::get('Get_User_Estate/{id}' ,[\App\Http\Controllers\PostsController::class , 'Get_User_Estates'] ) ;

route::get('Get_User_Cars/{id}' ,[\App\Http\Controllers\PostsController::class , 'Get_User_Cars'] ) ;


route::get('Get_Car/{id}' , [\App\Http\Controllers\PostsController::class,'get_car']) ;

route::get('Get_Estate/{id}' , [\App\Http\Controllers\PostsController::class,'get_estate']) ;


//Remove And Update Posts
route::get('Remove_Estate/{id}' , [\App\Http\Controllers\PostsController::class , 'remove_estate_post']) ;

route::get('Remove_Car/{id}' , [\App\Http\Controllers\PostsController::class , 'remove_car_post']) ;

Route::post('Update_Post' , [\App\Http\Controllers\PostsController::class , 'update_post']) ;


//Home route
route::get('Estate_Home' , [\App\Http\Controllers\HomeController::class,'Estate_Home']) ;

route::get('Car_Home' , [\App\Http\Controllers\HomeController::class,'Car_Home']) ;


