<?php

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

// Public Routes
Route::get('schedules', 'Api\PublicScheduleController@index')->name('api.schedules.index');
Route::get('masjid_profile', [App\Http\Controllers\Api\MasjidProfileController::class, 'show'])->name('api.masjid_profile.show');

// Authentication Routes...
Route::post('login', 'Api\Auth\LoginController@login')->name('api.login');

// Authentication using Laravel Passport
// I make in seperate controller and make in different route
// I think it's better without conflict the main project.

Route::post('auth/login', 'Api\Auth\AuthController@login')->name('api.auth.login');
Route::middleware('auth:api')->post('auth/logout', 'Api\Auth\AuthController@logout')->name('api.auth.logout');

Route::group(['middleware' => 'auth:api', 'as' => 'api.', 'namespace' => 'Api'], function () {
    /*
     * User Profile Endpoint
     */
    Route::get('user', 'Auth\ProfileController@show')->name('user');

    /*
     * Transctions Endpoints
     */
    Route::apiResource('transactions', 'TransactionsController');

    /*
     * Categories Endpoints
     */
    Route::resource('categories', 'CategoriesController')->names('categories');

    /*
     * Books Endpoints
     */
    Route::apiResource('books', 'BookController')->names('books');
});

Route::group(['middleware' => ['auth:api'], 'as' => 'api.'], function () {
    /*
     * Masjid Profile Endpoints
     */
    Route::post('masjid_profile/image', [App\Http\Controllers\Api\MasjidProfileController::class, 'updateLogo'])->name('masjid_profile.image');

    /*
     * Upload QRIS for Bank Account Endpoint
     */
    Route::post('bank_account/{bank_account}/qris_image', [App\Http\Controllers\Api\BankAccountController::class, 'updateQrisImage'])->name('bank_account.qris_image');
});
