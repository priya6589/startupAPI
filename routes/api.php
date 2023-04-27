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
    Route::post('register', [App\Http\Controllers\Api\UserController::class, 'userRegister']);
    Route::post('investor-register', [App\Http\Controllers\Api\UserController::class, 'investor_register']);
    Route::post('send-otp/{id}', [App\Http\Controllers\Api\UserController::class, 'send_otp']);
    Route::post('confirm-otp', [App\Http\Controllers\Api\UserController::class, 'confirm_otp']);
    Route::post('save-contact', [App\Http\Controllers\Api\UserController::class, 'save_contact']);
    Route::post('user-login', [App\Http\Controllers\Api\UserController::class, 'user_login']);
    Route::get('countries',[App\Http\Controllers\Api\CountryController::class,'all_countries']);
    Route::post('investor-profile',[App\Http\Controllers\Api\InvestorController::class,'investor_profile']);
    Route::post('reset-password',[App\Http\Controllers\Api\UserController::class,'reset_password']);
    Route::post('/check-user-email-verfication', [App\Http\Controllers\Api\UserController::class, 'check_user_email_verfication']);
    Route::post('/forget-password',[App\Http\Controllers\Api\UserController::class,'forget_password']);
    Route::post('/check-user-reset-password-verfication',[App\Http\Controllers\Api\UserController::class,'check_user_reset_password_verfication']);
    Route::post('/updated-reset-password',[App\Http\Controllers\Api\UserController::class,'updated_reset_password']);
});

Route::group(['middleware' => ['api']], function () {
    Route::post('personal-information',[App\Http\Controllers\Api\StartupController::class,'personal_information']);
    Route::post('business-information',[App\Http\Controllers\Api\StartupController::class,'business_information']);
    Route::get('get-business-information/{id}',[App\Http\Controllers\Api\StartupController::class,'get_business_information']);
    Route::get('get-basic-information/{id}',[App\Http\Controllers\Api\DocumentsController::class,'get_basic_information']);
    Route::post('basic-information',[App\Http\Controllers\Api\DocumentsController::class,'basic_information']);
    Route::post('bank-details',[App\Http\Controllers\Api\BankDetailsController::class,'bank_details']);
    Route::get('get-bank-information/{id}',[App\Http\Controllers\Api\BankDetailsController::class,'get_bank_information']);
    Route::post('investor-type-information',[App\Http\Controllers\Api\InvestorController::class,'investor_type_information']);
    Route::get('get-investor-type-information/{id}', [App\Http\Controllers\Api\InvestorController::class, 'get_investor_type_information']);
    Route::post('update-profile/{id}', [App\Http\Controllers\Api\UserController::class, 'update_profile']);
    Route::get('single-user/{id}', [App\Http\Controllers\Api\UserController::class, 'get_single_user']);
    Route::post('join_to_invest', [App\Http\Controllers\Api\UserController::class, 'join_to_invest']);
    Route::post('investor-angel-terms',[App\Http\Controllers\Api\InvestorController::class,'investor_angel_terms']);

    Route::post('store-bank-details', [App\Http\Controllers\Api\UserController::class, 'store_bank_detail']);
    Route::post('update-bank-details/{id}', [App\Http\Controllers\Api\UserController::class, 'update_bank_detail']);

    Route::post('document-upload/{id}', [App\Http\Controllers\Api\UserController::class, 'document_upload']);

    Route::post('update-business-details/{id}', [App\Http\Controllers\Api\UserController::class, 'business_detail_update']);

     // Countries route 
     Route::get('country/{id}',[App\Http\Controllers\Api\CountryController::class,'single_country']);

});