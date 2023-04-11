<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\auth\AuthController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => ['lang']] , function () {
    // public
    Route::post('/login',[AuthController::class,'login']);
    Route::post('/register',[AuthController::class,'register']);
    Route::post('/forget',[AuthController::class,'forget']);
    
    
    //protected
    Route::group(['middleware' => ['auth:sanctum']] , function () {
    
        Route::post('/logout',[AuthController::class,'logout']);
    
    
        // return $request->user();
    });
    
    });