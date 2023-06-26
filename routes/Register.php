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



Route::post('SignUp' , [\App\Http\Controllers\Register::class , 'SignUp'] ) ;

Route::post('LogIn' , [\App\Http\Controllers\Register::class , 'LogIn']) ;

Route::get('LogOut' , [\App\Http\Controllers\Register::class , 'LogOut'])->middleware('auth:sanctum') ;

Route::post('/user/password/email', [\App\Http\Controllers\ForgotPasswordController::class, 'userForgotPassword']);

Route::post('/user/password/code/check', [\App\Http\Controllers\ForgotPasswordController::class, 'userCheckCode']);

Route::post('/user/password/reset', [\App\Http\Controllers\ForgotPasswordController::class, 'userResetPassword']);

route::post('check_code_email_verify' , [\App\Http\Controllers\Register::class , 'check_code_email_verify']) ;
