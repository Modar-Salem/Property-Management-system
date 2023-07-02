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

//Rate
Route::post('Give_Rate' , [\App\Http\Controllers\Posts::class , 'rate']) ;

Route::post('Get_Rate', [\App\Http\Controllers\Posts::class ,'Get_Rate' ])  ;


//Favorite
Route::post('Add_To_Favorite' , [\App\Http\Controllers\Posts::class , 'Add_To_Favorite']) ;

Route::post('Get_All_Favorite' , [\App\Http\Controllers\Posts::class , 'Get_All_Favorite']) ;

route::post('Likes_number', [\App\Http\Controllers\Posts::class , 'Likes_number']) ;
