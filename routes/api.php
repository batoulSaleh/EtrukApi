<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\auth\AuthController;
use App\Http\Controllers\api\admin\AdCategoryController;
use App\Http\Controllers\api\admin\AdCaseController;
use App\Http\Controllers\api\admin\DonationTypeController;
use App\Http\Controllers\api\admin\AdDonationController;
use App\Http\Controllers\api\user\UsCategoryController;
use App\Http\Controllers\api\user\UsCaseController;
use App\Http\Controllers\api\user\UsDonationController;

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
    Route::post('/login/admin',[AuthController::class,'loginadmin']);

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

        Route::prefix('/donation')->group( function () {
            Route::get('/index',[AdDonationController::class,'index']);
            Route::get('/index/case/{caseid}',[AdDonationController::class,'indexOfCase']);
            Route::get('/show/{id}',[AdDonationController::class,'show']);
            Route::post('/accept/{id}',[AdDonationController::class,'acceptDonation'])->middleware('auth:sanctum');
        });

    });

    Route::prefix('/user')->group( function () {
    
        Route::prefix('/category')->group( function () {
            Route::get('/index',[UsCategoryController::class,'index']);
            Route::get('/show/{id}',[UsCategoryController::class,'show']);
        });

        Route::prefix('/case')->group( function () {
            Route::get('/index',[UsCaseController::class,'index']);
            Route::get('/show/{id}',[UsCaseController::class,'show']);
            Route::get('/category/{id}',[UsCaseController::class,'casesOfCategory']);
            Route::post('/store',[UsCaseController::class,'store'])->middleware('auth:sanctum');
            Route::post('/update/{id}',[UsCaseController::class,'update'])->middleware('auth:sanctum');
            Route::post('/destroy/{id}',[UsCaseController::class,'destroy'])->middleware('auth:sanctum');
        });

        Route::prefix('/donation')->group( function () {
            Route::get('/index',[UsDonationController::class,'index']);
            Route::get('/index/user',[UsDonationController::class,'indexOfUser'])->middleware('auth:sanctum');
            Route::get('/show/{id}',[UsDonationController::class,'show']);
            Route::post('/financial/user',[UsDonationController::class,'donatefinanciallyUser'])->middleware('auth:sanctum');
            Route::post('/financial/guest',[UsDonationController::class,'donatefinanciallyGuest']);
            Route::post('/volunteering/user',[UsDonationController::class,'volunteeringUser'])->middleware('auth:sanctum');
            Route::post('/volunteering/guest',[UsDonationController::class,'volunteeringGuest']);
            Route::post('/food/user',[UsDonationController::class,'foodUser'])->middleware('auth:sanctum');
            Route::post('/food/guest',[UsDonationController::class,'foodGuest']);
            Route::post('/clothes/user',[UsDonationController::class,'clothesUser'])->middleware('auth:sanctum');
            Route::post('/clothes/guest',[UsDonationController::class,'clothesGuest']);
            Route::post('/furniture/user',[UsDonationController::class,'furnitureUser'])->middleware('auth:sanctum');
            Route::post('/furniture/guest',[UsDonationController::class,'furnitureGuest']);
        });
    });

    //protected
    Route::group(['middleware' => ['auth:sanctum']] , function () {
    
        Route::post('/logout',[AuthController::class,'logout']);

    });
    
});