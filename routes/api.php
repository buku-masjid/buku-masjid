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
