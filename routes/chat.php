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


Route::post('sendMessage', [\App\Http\Controllers\ChatController::class, 'sendMessage']) ;

route::post('getConversation', [\App\Http\Controllers\ChatController::class, 'getConversation']) ;

route::get('getChattedPersons', [\App\Http\Controllers\ChatController::class, 'getChattedPersons']) ;
