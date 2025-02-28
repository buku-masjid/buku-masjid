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

// Authentication with sessions, specifically for AJAX from Blade.
Route::group(['middleware' => ['auth', 'web'], 'as' => 'api.'], function () {
    /*
     * Masjid Profile Endpoints
     */
    Route::post('masjid_profile/upload_logo', [App\Http\Controllers\Api\MasjidProfileController::class, 'updateLogo'])->name('masjid_profile.upload_logo');
    Route::post('masjid_profile/upload_photo', [App\Http\Controllers\Api\MasjidProfileController::class, 'updatePhoto'])->name('masjid_profile.upload_photo');

    /*
     * Upload QRIS for Bank Account Endpoint
     */
    Route::post('bank_account/{bank_account}/qris_image', [App\Http\Controllers\Api\BankAccountController::class, 'updateQrisImage'])->name('bank_account.qris_image');

    Route::post('books/{book}/upload_poster_image', [App\Http\Controllers\Api\BookController::class, 'updatePosterImage'])->name('books.upload_poster_image');
    Route::post('books/{book}/upload_thumbnail_image', [App\Http\Controllers\Api\BookController::class, 'updateThumbnailImage'])->name('books.upload_thumbnail_image');
});

if (config('features.shalat_time.is_active')) {
    Route::group(['as' => 'api.'], function () {
        Route::get('shalat_time', [App\Http\Controllers\Api\PublicShalatTimeController::class, 'show'])
            ->name('public_shalat_time.show');
    });
}
