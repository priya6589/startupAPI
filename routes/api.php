<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group(['middleware' => 'api'], function () {
    
    Route::post('startup-register', [App\Http\Controllers\Api\UserController::class, 'startup_register']);
    Route::post('investor-register', [App\Http\Controllers\Api\UserController::class, 'investor_register']);
    Route::get('user-login', [App\Http\Controllers\Api\UserController::class, 'user_login']);

    Route::post('update-profile/{id}', [App\Http\Controllers\Api\UserController::class, 'update_profile']);
    Route::get('single-user/{id}', [App\Http\Controllers\Api\UserController::class, 'get_single_user']);

    Route::post('store-bank-details', [App\Http\Controllers\Api\UserController::class, 'store_bank_detail']);
    Route::post('update-bank-details/{id}', [App\Http\Controllers\Api\UserController::class, 'update_bank_detail']);

    Route::post('document-upload/{id}', [App\Http\Controllers\Api\UserController::class, 'document_upload']);

    Route::post('update-business-details/{id}', [App\Http\Controllers\Api\UserController::class, 'business_detail_update']);
});

