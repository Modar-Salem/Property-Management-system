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

route::post('Likes_number', [\App\Http\Controllers\Posts::class , 'Likes_number']) ;

route::get('Get_User_Cars/{id}' ,[\App\Http\Controllers\Posts::class , 'Get_User_Cars'] ) ;

route::get('Remove_Estate/{id}' , [\App\Http\Controllers\Posts::class , 'remove_estate_posts']) ;

route::get('Remove_Car/{id}' , [\App\Http\Controllers\Posts::class , 'remove_car_posts']) ;

route::get('Estate_Home' , [\App\Http\Controllers\HomeController::class,'Estate_Home']) ;

route::get('Car_Home' , [\App\Http\Controllers\HomeController::class,'Car_Home']) ;

route::get('Get_Car/{id}' , [\App\Http\Controllers\Posts::class,'get_car']) ;

route::get('Get_Estate/{id}' , [\App\Http\Controllers\Posts::class,'get_estate']) ;

Route::post('Give_Rate' , [\App\Http\Controllers\Posts::class , 'rate']) ;

Route::post('Get_Rate', [\App\Http\Controllers\Posts::class ,'Get_Rate' ])  ;

Route::post('Add_To_Favorite' , [\App\Http\Controllers\Posts::class , 'Add_To_Favorite']) ;

Route::post('Get_All_Favorite' , [\App\Http\Controllers\Posts::class , 'Get_All_Favorite']) ;

Route::post('Update_Post' , [\App\Http\Controllers\Posts::class , 'update_post']) ;
