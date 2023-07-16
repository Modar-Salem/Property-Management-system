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


route::get('RemoveUser/{id}' , [\App\Http\Controllers\ProfileController::class , 'destroy']) ;

route::post('UpdateProfile' , [\App\Http\Controllers\ProfileController::class , 'update']) ;

route::post('Insert_Image' , [\App\Http\Controllers\ProfileController::class , 'User_insert_image']) ;

route::get('GetUser/{id}' , [\App\Http\Controllers\ProfileController::class , 'GetUser']) ;

route::get('profile' , [\App\Http\Controllers\ProfileController::class , 'profile']) ;

route::post('reset_password' , [\App\Http\Controllers\ProfileController::class , 'resetPassword']) ;

route::post('My_posts' , [\App\Http\Controllers\ProfileController::class , 'my_posts']) ;

