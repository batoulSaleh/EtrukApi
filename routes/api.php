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
use App\Http\Controllers\api\charity\ChCategoryController;
use App\Http\Controllers\api\charity\ChDonationTypeController;
use App\Http\Controllers\api\charity\ChCaseController;
use App\Http\Controllers\api\charity\ChDonationController;
use App\Http\Controllers\api\charity\ChProfileController;
use App\Http\Controllers\api\charity\ChEventController;

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

    Route::prefix('/charity')->group(function () {

        Route::prefix('/category')->group(function () {
            Route::get('/index', [ChCategoryController::class, 'index']);
            Route::get('/show/{id}', [ChCategoryController::class, 'show']);
        });

        Route::prefix('/donationtype')->group(function () {
            Route::get('/index', [ChDonationTypeController::class, 'index']);
            Route::get('/show/{id}', [ChDonationTypeController::class, 'show']);
        });

        Route::prefix('/case')->group(function () {
            Route::get('/index', [ChCaseController::class, 'index'])->middleware('auth:sanctum');
            Route::get('/show/{id}', [ChCaseController::class, 'show']);
            Route::post('/store', [ChCaseController::class, 'store'])->middleware('auth:sanctum');
            Route::post('/update/{id}', [ChCaseController::class, 'update'])->middleware('auth:sanctum');
            Route::post('/destroy/{id}', [ChCaseController::class, 'destroy'])->middleware('auth:sanctum');
        });

        Route::prefix('/event')->group(function () {
            Route::get('/index', [ChEventController::class, 'index'])->middleware('auth:sanctum');
            Route::get('/show/{id}', [ChEventController::class, 'show']);
            Route::post('/store', [ChEventController::class, 'store'])->middleware('auth:sanctum');
            Route::post('/update/{id}', [ChEventController::class, 'update'])->middleware('auth:sanctum');
            Route::post('/destroy/{id}', [ChEventController::class, 'destroy'])->middleware('auth:sanctum');
        });

        Route::prefix('/donation')->group(function () {
            Route::get('/index', [ChDonationController::class, 'index'])->middleware('auth:sanctum');
            Route::post('accept/{id}', [ChDonationController::class, 'accept'])->middleware('auth:sanctum');
        });

        Route::prefix('/profile')->group(function () {
            Route::get('/show', [ChProfileController::class, 'show'])->middleware('auth:sanctum');
            Route::post('/update', [ChProfileController::class, 'update'])->middleware('auth:sanctum');
        });

    });

    Route::prefix('/dashboard')->group(function () {

        Route::prefix('/zakat')->group(function () {
            Route::post('/update', [AdZakatController::class, 'update'])->middleware(['auth:sanctum','admin']);
        });

        Route::prefix('/mazad')->group(function () {
            Route::get('/index', [AdMazadController::class, 'index'])->middleware(['auth:sanctum','admin']);
            Route::get('/show/{id}', [AdMazadController::class, 'show'])->middleware(['auth:sanctum','admin']);
            Route::post('/update/{id}', [AdMazadController::class, 'update'])->middleware(['auth:sanctum','admin']);
            Route::post('/destroy/{id}', [AdMazadController::class, 'destroy'])->middleware(['auth:sanctum','admin']);
        });

        Route::prefix('/charity')->group(function () {
            Route::get('/index', [AdCharityController::class, 'index'])->middleware(['auth:sanctum','admin']);
            Route::get('/show/{id}', [AdCharityController::class, 'show'])->middleware(['auth:sanctum','admin']);
        });

        Route::prefix('/category')->group(function () {
            Route::get('/index', [AdCategoryController::class, 'index'])->middleware(['auth:sanctum','admin']);
            Route::get('/show/{id}', [AdCategoryController::class, 'show'])->middleware(['auth:sanctum','admin']);
            Route::post('/store', [AdCategoryController::class, 'store'])->middleware(['auth:sanctum','admin']);
            Route::post('/update/{id}', [AdCategoryController::class, 'update'])->middleware(['auth:sanctum','admin']);
            Route::post('/destroy/{id}', [AdCategoryController::class, 'destroy'])->middleware(['auth:sanctum','admin']);
        });

        Route::prefix('/case')->group(function () {
            Route::get('/index', [AdCaseController::class, 'index'])->middleware(['auth:sanctum','admin']);
            Route::get('/show/{id}', [AdCaseController::class, 'show'])->middleware(['auth:sanctum','admin']);
            Route::post('/store', [AdCaseController::class, 'store'])->middleware(['auth:sanctum','admin']);
            Route::post('/update/{id}', [AdCaseController::class, 'update'])->middleware(['auth:sanctum','admin']);
            Route::post('/destroy/{id}', [AdCaseController::class, 'destroy'])->middleware(['auth:sanctum','admin']);
        });

        Route::prefix('/donationtype')->group(function () {
            Route::get('/index', [DonationTypeController::class, 'index'])->middleware(['auth:sanctum','admin']);
            Route::get('/show/{id}', [DonationTypeController::class, 'show'])->middleware(['auth:sanctum','admin']);
            Route::post('/store', [DonationTypeController::class, 'store'])->middleware(['auth:sanctum','admin']);
            Route::post('/update/{id}', [DonationTypeController::class, 'update'])->middleware(['auth:sanctum','admin']);
            Route::post('/destroy/{id}', [DonationTypeController::class, 'destroy'])->middleware(['auth:sanctum','admin']);
        });

        Route::prefix('/donation')->group(function () {
            Route::get('/index', [AdDonationController::class, 'index'])->middleware(['auth:sanctum','admin']);
            Route::get('/index/case/{caseid}', [AdDonationController::class, 'indexOfCase'])->middleware(['auth:sanctum','admin']);
            Route::get('/show/{id}', [AdDonationController::class, 'show'])->middleware(['auth:sanctum','admin']);
            Route::post('/accept/{id}', [AdDonationController::class, 'acceptDonation'])->middleware(['auth:sanctum','admin']);
            Route::get('/payments', [AdDonationController::class, 'allPayments'])->middleware(['auth:sanctum','admin']);
        });

        Route::prefix('/volunteer')->group(function () {
            Route::get('/index', [AdVolunteerController::class, 'index'])->middleware(['auth:sanctum','admin']);
            Route::get('/show/{id}', [AdVolunteerController::class, 'show'])->middleware(['auth:sanctum','admin']);
            Route::post('/destroy/{id}', [AdVolunteerController::class, 'destroy'])->middleware(['auth:sanctum','admin']);
        });

        Route::prefix('/events')->group(function () {
            Route::get('/index', [AdEventController::class, 'index'])->middleware(['auth:sanctum','admin']);
            Route::get('/show/{id}', [AdEventController::class, 'show'])->middleware(['auth:sanctum','admin']);
            Route::post('/store', [AdEventController::class, 'store'])->middleware(['auth:sanctum','admin']);
            Route::post('/update/{id}', [AdEventController::class, 'update'])->middleware(['auth:sanctum','admin']);
            Route::post('/destroy/{id}', [AdEventController::class, 'destroy'])->middleware(['auth:sanctum','admin']);
        });
    });

    Route::prefix('/user')->group(function () {

        Route::prefix('/mazad')->group(function () {
            Route::get('/index', [UsMazadController::class, 'index']);
            Route::post('/store', [UsMazadController::class, 'store'])->middleware('auth:sanctum');
            Route::get('/latestshow', [UsMazadController::class, 'latestshow']);
            Route::get('/get/money', [UsMazadController::class, 'getmoney']);
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
            Route::get('/donation/types', [UsDonationController::class, 'donationTypes']);
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
});
