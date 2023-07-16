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

//Search And Fiter
route::post('search' , [\App\Http\Controllers\Search_FilterController::class , 'Search']);

route::post('filter' , [\App\Http\Controllers\Search_FilterController::class , 'Filter']);



