<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\auth\AuthController;
use App\Http\Controllers\api\admin\AdCategoryController;
use App\Http\Controllers\api\admin\AdCaseController;
use App\Http\Controllers\api\admin\DonationTypeController;
use App\Http\Controllers\api\user\UsCategoryController;
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
    
    Route::prefix('/dashboard')->group( function () {
    
        Route::prefix('/category')->group( function () {
            Route::get('/index',[AdCategoryController::class,'index']);
            Route::get('/show/{id}',[AdCategoryController::class,'show']);
            Route::post('/store',[AdCategoryController::class,'store']);
            Route::post('/update/{id}',[AdCategoryController::class,'update']);
            Route::post('/destroy/{id}',[AdCategoryController::class,'destroy']);
        });

        Route::prefix('/case')->group( function () {
            Route::get('/index',[AdCaseController::class,'index']);
            Route::get('/show/{id}',[AdCaseController::class,'show']);
            Route::post('/store',[AdCaseController::class,'store']);
            Route::post('/update/{id}',[AdCaseController::class,'update']);
            Route::post('/destroy/{id}',[AdCaseController::class,'destroy']);
        });

        Route::prefix('/donationtype')->group( function () {
            Route::get('/index',[DonationTypeController::class,'index']);
            Route::get('/show/{id}',[DonationTypeController::class,'show']);
            Route::post('/store',[DonationTypeController::class,'store']);
            Route::post('/update/{id}',[DonationTypeController::class,'update']);
            Route::post('/destroy/{id}',[DonationTypeController::class,'destroy']);
        });

    });

    Route::prefix('/user')->group( function () {
    
        Route::prefix('/category')->group( function () {
            Route::get('/index',[UsCategoryController::class,'index']);
            Route::get('/show/{id}',[UsCategoryController::class,'show']);
        });

    });

    //protected
    Route::group(['middleware' => ['auth:sanctum']] , function () {
    
        Route::post('/logout',[AuthController::class,'logout']);

    });
    
});