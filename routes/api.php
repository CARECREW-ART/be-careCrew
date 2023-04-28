<?php

use App\Http\Controllers\Assistant\AssistantController;
use App\Http\Controllers\Authentication\AuthenticationController;
use App\Http\Controllers\Master\MasterController;
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

Route::prefix('auth')->group(function () {
    Route::controller(AuthenticationController::class)->group(function () {
        Route::post('/login', 'login');
        Route::post('/logout', 'logout')->middleware('auth:sanctum');
    });
});

Route::controller(AssistantController::class)->group(function () {
    Route::post('/assistant', 'createAssistant');
});

Route::prefix('master')->group(function () {
    Route::controller(MasterController::class)->group(function () {
        Route::get('/bank', 'getBank');
        Route::get('/province', 'getProvince');
        Route::get('/city', 'getCityByProvinceId');
        Route::get('/district', 'getDistrictByCityId');
        Route::get('/village', 'getVillageByDistrictId');
        Route::get('/postalzip', 'getPostalZipByVillageId');
    });
});



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
