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
route::get('RemoveUser/{id}' , [\App\Http\Controllers\Register::class , 'destroy']) ;

route::post('UpdateProfile' , [\App\Http\Controllers\Register::class , 'update']) ;

route::post('Insert_Image' , [\App\Http\Controllers\Register::class , 'insert_image']) ;

route::get('GetUser/{id}' , [\App\Http\Controllers\Register::class , 'GetUser']) ;


