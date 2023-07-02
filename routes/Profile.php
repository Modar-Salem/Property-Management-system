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
route::get('RemoveUser/{id}' , [\App\Http\Controllers\Profile::class , 'destroy']) ;

route::post('UpdateProfile' , [\App\Http\Controllers\Profile::class , 'update']) ;

route::post('Insert_Image' , [\App\Http\Controllers\Profile::class , 'insert_image']) ;

route::get('GetUser/{id}' , [\App\Http\Controllers\Profile::class , 'GetUser']) ;

route::get('profile' , [\App\Http\Controllers\Profile::class , 'profile']) ;

route::post('reset_password' , [\App\Http\Controllers\Profile::class , 'resetPassword']) ;

route::post('My_posts' , [\App\Http\Controllers\Profile::class , 'my_posts']) ;

