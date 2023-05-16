<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\user\UsZakatController;
use App\Http\Controllers\api\auth\AuthController;
use App\Http\Controllers\api\user\UsCaseController;
use App\Http\Controllers\api\admin\AdCaseController;
use App\Http\Controllers\api\user\UsEventController;
use App\Http\Controllers\api\admin\AdEventController;
use App\Http\Controllers\api\user\UsCharityController;
use App\Http\Controllers\api\user\UsProfileController;
use App\Http\Controllers\api\admin\AdCharityController;
use App\Http\Controllers\api\user\UsCategoryController;
use App\Http\Controllers\api\user\UsDonationController;
use App\Http\Controllers\api\admin\AdCategoryController;
use App\Http\Controllers\api\admin\AdDonationController;
use App\Http\Controllers\api\user\UsVolunteerController;
use App\Http\Controllers\api\admin\AdVolunteerController;
use App\Http\Controllers\api\admin\DonationTypeController;
use App\Http\Controllers\api\admin\AdMazadController;
use App\Http\Controllers\api\admin\AdZakatController;
use App\Http\Controllers\api\user\UsMazadController;


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

Route::group(['middleware' => ['lang']], function () {
    // public
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/forget', [AuthController::class, 'forget']);
    Route::post('/login/admin', [AuthController::class, 'loginadmin']);

    Route::prefix('/dashboard')->group(function () {

        Route::prefix('/zakat')->group(function () {
            Route::post('/update', [AdZakatController::class, 'update']);
        });

        Route::prefix('/mazad')->group(function () {
            Route::get('/index', [AdMazadController::class, 'index']);
            Route::get('/show/{id}', [AdMazadController::class, 'show']);
            Route::post('/update/{id}', [AdMazadController::class, 'update']);
            Route::post('/destroy/{id}', [AdMazadController::class, 'destroy']);
        });

        Route::prefix('/charity')->group(function () {
            Route::get('/index', [AdCharityController::class, 'index']);
            Route::get('/show/{id}', [AdCharityController::class, 'show']);
            Route::get('/showto/update', [AdCharityController::class, 'showUpdate'])->middleware('auth:sanctum');
            Route::get('/cases', [AdCharityController::class, 'getcases'])->middleware('auth:sanctum');
            Route::get('/events', [AdCharityController::class, 'getEvents'])->middleware('auth:sanctum');
            Route::post('/store/event', [AdCharityController::class, 'storeEvent'])->middleware('auth:sanctum');
            Route::post('/edit', [AdCharityController::class, 'edit'])->middleware('auth:sanctum');
        });

        Route::prefix('/category')->group(function () {
            Route::get('/index', [AdCategoryController::class, 'index']);
            Route::get('/show/{id}', [AdCategoryController::class, 'show']);
            Route::post('/store', [AdCategoryController::class, 'store']);
            Route::post('/update/{id}', [AdCategoryController::class, 'update']);
            Route::post('/destroy/{id}', [AdCategoryController::class, 'destroy']);
        });

        Route::prefix('/case')->group(function () {
            Route::get('/index', [AdCaseController::class, 'index']);
            Route::get('/show/{id}', [AdCaseController::class, 'show']);
            Route::post('/store', [AdCaseController::class, 'store']);
            Route::post('/update/{id}', [AdCaseController::class, 'update']);
            Route::post('/destroy/{id}', [AdCaseController::class, 'destroy']);
        });

        Route::prefix('/donationtype')->group(function () {
            Route::get('/index', [DonationTypeController::class, 'index']);
            Route::get('/show/{id}', [DonationTypeController::class, 'show']);
            Route::post('/store', [DonationTypeController::class, 'store']);
            Route::post('/update/{id}', [DonationTypeController::class, 'update']);
            Route::post('/destroy/{id}', [DonationTypeController::class, 'destroy']);
        });

        Route::prefix('/donation')->group(function () {
            Route::get('/index', [AdDonationController::class, 'index']);
            Route::get('/index/case/{caseid}', [AdDonationController::class, 'indexOfCase']);
            Route::get('/show/{id}', [AdDonationController::class, 'show']);
            Route::post('/accept/{id}', [AdDonationController::class, 'acceptDonation'])->middleware('auth:sanctum');
            Route::get('/payments', [AdDonationController::class, 'allPayments']);
        });

        Route::prefix('/volunteer')->group(function () {
            Route::get('/index', [AdVolunteerController::class, 'index']);
            Route::get('/show/{id}', [AdVolunteerController::class, 'show']);
            Route::post('/destroy/{id}', [AdVolunteerController::class, 'destroy']);
        });

        Route::prefix('/events')->group(function () {
            Route::get('/index', [AdEventController::class, 'index']);
            Route::get('/show/{id}', [AdEventController::class, 'show']);
            Route::post('/store', [AdEventController::class, 'store']);
            Route::post('/update/{id}', [AdEventController::class, 'update']);
            Route::post('/destroy/{id}', [AdEventController::class, 'destroy']);
        });
    });

    Route::prefix('/user')->group(function () {

        Route::prefix('/mazad')->group(function () {
            Route::get('/index', [UsMazadController::class, 'index']);
            Route::post('/store', [UsMazadController::class, 'store'])->middleware('auth:sanctum');
            Route::get('/latestshow', [UsMazadController::class, 'latestshow']);
            Route::get('/show/{id}', [UsMazadController::class, 'show']);
            Route::get('/othermazad/{id}', [UsMazadController::class, 'auctionsOfUser']);
            Route::post('/increment/{id}', [UsMazadController::class, 'mazadIncrement'])->middleware('auth:sanctum');
            Route::get('/history/{id}', [UsMazadController::class, 'historyOfMazad']);
            });


        Route::prefix('/charity')->group(function () {
            Route::get('/index', [UsCharityController::class, 'index']);
            Route::get('/show/{id}', [UsCharityController::class, 'show']);
            Route::get('/cases/{id}', [UsCharityController::class, 'getCases']);
            Route::get('/events/{id}', [UsCharityController::class, 'getEvents']);
        });

        Route::prefix('/profile')->group(function () {
            Route::get('/show', [UsProfileController::class, 'show'])->middleware('auth:sanctum');
            Route::post('/edit', [UsProfileController::class, 'edit'])->middleware('auth:sanctum');
            Route::get('/cases', [UsProfileController::class, 'casesOfUser'])->middleware('auth:sanctum');
            Route::get('/donations', [UsProfileController::class, 'donationsOfUser'])->middleware('auth:sanctum');
        });

        Route::prefix('/category')->group(function () {
            Route::get('/index', [UsCategoryController::class, 'index']);
            Route::get('/show/{id}', [UsCategoryController::class, 'show']);
        });

        Route::prefix('/case')->group(function () {
            Route::get('/index', [UsCaseController::class, 'index']);
            Route::get('/last', [UsCaseController::class, 'lastCases']);
            Route::get('/show/{id}', [UsCaseController::class, 'show']);
            Route::get('/show/update/{id}', [UsCaseController::class, 'showUpdate']);
            Route::get('/category/{id}', [UsCaseController::class, 'casesOfCategory']);
            Route::get('/donation/{id}', [UsCaseController::class, 'casesOfDonationtype']);
            Route::get('/category/donation/{categoryid}/{donationtypeid}', [UsCaseController::class, 'casesOfCategoryandDonationtype']);
            Route::post('/store', [UsCaseController::class, 'store'])->middleware('auth:sanctum');
            Route::post('/update/{id}', [UsCaseController::class, 'update'])->middleware('auth:sanctum');
            Route::post('/destroy/{id}', [UsCaseController::class, 'destroy'])->middleware('auth:sanctum');
        });

        Route::prefix('/donation')->group(function () {
            Route::get('/index', [UsDonationController::class, 'index']);
            Route::get('/money', [UsDonationController::class, 'getmoney']);
            Route::get('/index/user', [UsDonationController::class, 'indexOfUser'])->middleware('auth:sanctum');
            Route::get('/show/{id}', [UsDonationController::class, 'show']);
            Route::post('/financial/user', [UsDonationController::class, 'donatefinanciallyUser'])->middleware('auth:sanctum');
            Route::post('/financial/guest', [UsDonationController::class, 'donatefinanciallyGuest']);
            Route::post('/volunteering/user', [UsDonationController::class, 'volunteeringUser'])->middleware('auth:sanctum');
            Route::post('/volunteering/guest', [UsDonationController::class, 'volunteeringGuest']);
            Route::post('/food/user', [UsDonationController::class, 'foodUser'])->middleware('auth:sanctum');
            Route::post('/food/guest', [UsDonationController::class, 'foodGuest']);
            Route::post('/clothes/user', [UsDonationController::class, 'clothesUser'])->middleware('auth:sanctum');
            Route::post('/clothes/guest', [UsDonationController::class, 'clothesGuest']);
            Route::post('/furniture/user', [UsDonationController::class, 'furnitureUser'])->middleware('auth:sanctum');
            Route::post('/furniture/guest', [UsDonationController::class, 'furnitureGuest']);
            Route::post('/store/payment', [UsDonationController::class, 'storePayment']);

        });

        Route::prefix('/volunteer')->group(function () {
            Route::get('/get/user', [UsVolunteerController::class, 'getUser'])->middleware('auth:sanctum');
            Route::post('/store/user', [UsVolunteerController::class, 'storeUser'])->middleware('auth:sanctum');
            Route::post('/store/guest', [UsVolunteerController::class, 'storeGuest']);
        });

        Route::prefix('/event')->group(function () {
            Route::get('/index', [UsEventController::class, 'index']);
            Route::get('/show/{id}', [UsEventController::class, 'show']);
            Route::get('/latest/events', [UsEventController::class, 'showLatestEvents']);
            Route::get('/join/{id}', [UsEventController::class, 'joinToEvent'])->middleware('auth:sanctum');
        });

        Route::prefix('/zakat')->group(function () {
            Route::post('/calculate', [UsZakatController::class, 'calculate']);
        });
    });

    //protected
    Route::group(['middleware' => ['auth:sanctum']], function () {

        Route::post('/logout', [AuthController::class, 'logout']);
    });
    // Route::post('/calculate', [ZakatController::class, 'calculate']);
});
