<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::view('/', 'welcome')->name('welcome')->middleware('guest');

Auth::routes();

// Change Password Routes
Route::get('change-password', 'Auth\ChangePasswordController@show');
Route::patch('change-password', 'Auth\ChangePasswordController@update')->name('password.change');

Route::group(['middleware' => 'auth'], function () {
    Route::get('/home', 'TransactionsController@index')->name('home');

    /*
     * User Profile Routes
     */
    Route::get('profile', 'Auth\ProfileController@show')->name('profile.show');
    Route::get('profile/edit', 'Auth\ProfileController@edit')->name('profile.edit');
    Route::patch('profile/update', 'Auth\ProfileController@update')->name('profile.update');

    /*
     * Transactions Routes
     */
    Route::get('transaction_search', 'TransactionSearchController@index')->name('transaction_search.index');
    Route::get('transactions/export-csv', 'Transactions\ExportController@csv')->name('transactions.exports.csv');
    Route::resource('transactions', 'TransactionsController');

    /*
     * Categories Routes
     */
    Route::get('categories/{category}/export-csv', 'Transactions\ExportController@byCategory')->name('transactions.exports.by_category');
    Route::resource('categories', 'CategoriesController');

    /*
     * Loans Routes
     */
    Route::resource('loans', 'LoanController');
    Route::post('loans/{loan}/transactions', 'Loans\TransactionController@store')
        ->name('loans.transactions.store');

    /*
     * Report Routes
     */
    Route::get('/report', 'ReportsController@index')->name('reports.index');

    /*
     * Partners Routes
     */
    Route::get('partners/{partner}/export-csv', 'Transactions\ExportController@byPartner')->name('transactions.exports.by_partner');
    Route::resource('partners', 'PartnerController');

    /*
     * Lang switcher routes
     */
    Route::patch('lang_switch', 'LangSwitcherController@update')->name('lang.switch');
});
