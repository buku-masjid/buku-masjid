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

Route::view('/', 'guest.welcome');

Auth::routes(['register' => false, 'reset' => false]);

Route::group(['prefix' => 'laporan-kas', 'as' => 'public_reports.'], function () {
    Route::get('/', 'PublicReportController@index')->name('index');
    Route::get('/bulanan', 'PublicReportController@inMonths')->name('in_months');
    Route::get('/mingguan', 'PublicReportController@inWeeks')->name('in_weeks');
    Route::get('/per_kategori', 'PublicReportController@inOut')->name('in_out');
});

Route::group(['prefix' => 'jadwal', 'as' => 'public_schedules.'], function () {
    Route::get('/', 'PublicScheduleController@today')->name('index');
    Route::get('/hari_ini', 'PublicScheduleController@today')->name('today');
    Route::get('/besok', 'PublicScheduleController@tomorrow')->name('tomorrow');
    Route::get('/pekan_ini', 'PublicScheduleController@thisWeek')->name('this_week');
    Route::get('/pekan_depan', 'PublicScheduleController@nextWeek')->name('next_week');
});

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
     * Report Routes
     */
    Route::group(['prefix' => 'report'], function () {
        Route::get('/', 'ReportsController@inMonths')->name('reports.index');
        Route::get('/in_months', 'ReportsController@inMonths')->name('reports.in_months');
        Route::get('/in_months_pdf', 'ReportsController@inMonthsPdf')->name('reports.in_months_pdf');
        Route::get('/in_out', 'ReportsController@inOut')->name('reports.in_out');
        Route::get('/in_out_pdf', 'ReportsController@inOutPdf')->name('reports.in_out_pdf');
        Route::get('/in_weeks', 'ReportsController@inWeeks')->name('reports.in_weeks');
        Route::get('/in_weeks_pdf', 'ReportsController@inWeeksPdf')->name('reports.in_weeks_pdf');
    });

    /*
     * Books Routes
     */
    Route::post('book_switcher', 'BookSwitcherController@store')->name('book_switcher.store');
    Route::get('books/{book}/export-csv', 'Transactions\ExportController@byBook')->name('transactions.exports.by_book');
    Route::patch('books_report_titles/{book}', 'Books\ReportTitleController@update')->name('books.report_titles.update');
    Route::resource('books', 'BookController');

    /*
     * Lang switcher routes
     */
    Route::patch('lang_switch', 'LangSwitcherController@update')->name('lang.switch');

    /*
     * Bank Accounts Routes
     */
    Route::apiResource('bank_accounts', 'BankAccountController');
    Route::apiResource('bank_accounts.balances', 'BankAccounts\BalanceController');

    /*
     * LecturingSchedules Routes
     */
    Route::resource('friday_lecturing_schedules', App\Http\Controllers\FridayLecturingScheduleController::class)
        ->parameters(['friday_lecturing_schedules' => 'lecturing_schedule'])
        ->only(['create', 'store', 'show', 'edit', 'update']);
    Route::resource('lecturing_schedules', App\Http\Controllers\LecturingScheduleController::class);

    Route::get('masjid_profile', [App\Http\Controllers\MasjidProfileController::class, 'show'])->name('masjid_profile.show');
    Route::get('masjid_profile/edit', [App\Http\Controllers\MasjidProfileController::class, 'edit'])->name('masjid_profile.edit');
    Route::patch('masjid_profile', [App\Http\Controllers\MasjidProfileController::class, 'update'])->name('masjid_profile.update');
});
